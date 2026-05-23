<?php

namespace App\Http\Controllers;

use App\Enums\StatusSkpEnum;
use App\Enums\UnitOrganisasiEnum;
use App\Repositories\SkpPenyusunanRepository;
use App\Repositories\SkpRepository;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SkpPenyusunanController extends Controller
{
    protected SkpPenyusunanRepository $skpPenyusunanRepository;
    protected SkpRepository $skpRepository;

    public function __construct(
        SkpPenyusunanRepository $skpPenyusunanRepository,
        SkpRepository $skpRepository,
    )
    {
        $this->skpPenyusunanRepository = $skpPenyusunanRepository;
        $this->skpRepository = $skpRepository;
    }

    public function index(Request $request)
    {
        $listUnor = UnitOrganisasiEnum::cases();
        $listStatusSkp = StatusSkpEnum::cases();

        $skpPenyusunan = $this->skpPenyusunanRepository->with('masterSkp')->get();

        // Filter Unit Kerja
        $listUnker = [];
        foreach ($listUnor as $unor) {
            $listUnker[$unor->value] = $unor->getUnitKerja();
        }

        $skpPenyusunan = $this->skpPenyusunanRepository->skpPenyusunanFilter(
            $request->input('unit_organisasi'),
            $request->input('unit_kerja'),
            $request->input('status_skp'),
            $request->input('eselon'),
        );

        // Rekapitulasi status SKP
        $arrStatusSkp = array_column($listStatusSkp, 'value');
        $rekapStatusSkp = [];

        foreach ($arrStatusSkp as $s) {
            $key = Str::slug($s, '_');
            $rekapStatusSkp[$key] = $skpPenyusunan->where("status_skp", $s);
        }

        return view('monitoring_penyusunan', compact(
            'skpPenyusunan',
            'listUnor',
            'listUnker',
            'listStatusSkp',
            'rekapStatusSkp',
        ));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls',
        ]);
        
        try{
            // Load excel file
            $spreadsheet = IOFactory::load($request->file('file_excel')->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Normalisasi header agar tidak case-sensitive
            $header = $rows[0];
            $normalizedHeader = array_map(function ($value) {
                return mb_strtolower(trim((string) $value));
            }, $header);

            $findColumn = function (array $names) use ($normalizedHeader) {
                foreach ($names as $name) {
                    $index = array_search(mb_strtolower($name), $normalizedHeader, true);
                    if ($index !== false) {
                        return $index;
                    }
                }
                return null;
            };

            // Handling tahun
            $periode = "01-01-2026 s.d. 31-12-2026";
            $tanggalAwal = explode(' s.d. ', $periode)[0];
            $tahun = Carbon::createFromFormat('d-m-Y', $tanggalAwal)->year;

            $map = [
                'nama'           => $findColumn(['nama']),
                'nip'            => $findColumn(['nip']),
                'status_pegawai' => $findColumn(['status pegawai']),
                'golongan'       => $findColumn(['golongan']),
                'jabatan'        => $findColumn(['jabatan']),
                'unit_kerja'     => $findColumn(['Unit (Pegawai / SKP)']),
                'eselon'         => $findColumn(['eselon']),
                'tagging_atasan' => $findColumn(['Tagging Atasan (Struktur)']),
                'ppk'            => $findColumn(['Pejabat Penilai Kinerja (SKP)']),
                'periode'        => $findColumn(['Periode SKP']),
                'tahun'          => $tahun,
                'status_skp'     => $findColumn(['Status SKP']),
            ];

            $lastData = [
                'nama' => '',
                'nip'  => '',
            ];

            foreach (array_slice($rows,1) as $row) {
                $currentNama = isset($map['nama']) ? trim((string)($row[$map['nama']] ?? '')) : '';
                $currentNip  = isset($map['nip']) ? trim((string)($row[$map['nip']] ?? '')) : '';

                if (!empty($currentNama)) {
                    $lastData['nama'] = $currentNama;
                }
                if (!empty($currentNip)) {
                    $lastData['nip'] = $currentNip;
                }

                if (empty($lastData['nama'])) {
                    continue;
                }

                // Handling unit organisasi
                $unit_kerja = $row[$map['unit_kerja']];
                $unitOrganisasi = UnitOrganisasiEnum::getUnitOrganisasi($unit_kerja)->value ?? null;

                $dataSkp = [
                    'nama'           => $lastData['nama'],
                    'nip'            => $lastData['nip'],
                    'status_pegawai' => ($map['status_pegawai'] !== null) ? (string)($row[$map['status_pegawai']] ?? '') : '',
                    'jabatan'        => ($map['jabatan'] !== null) ? (string)($row[$map['jabatan']] ?? '') : '',
                    'golongan'       => ($map['golongan'] !== null) ? (string)($row[$map['golongan']] ?? '') : '',
                    'unit_kerja'     => ($map['unit_kerja'] !== null) ? (string)($row[$map['unit_kerja']] ?? '') : '',
                    'unit_organisasi' => $unitOrganisasi ? $unitOrganisasi : '',
                    'eselon'         => ($map['eselon'] !== null) ? (string)($row[$map['eselon']] ?? '') : '',
                    'tagging_atasan' => ($map['tagging_atasan'] !== null) ? (string)($row[$map['tagging_atasan']] ?? '') : '',
                    'ppk'            => ($map['ppk'] !== null) ? (string)($row[$map['ppk']] ?? '') : '',
                    'periode'        => ($map['periode'] !== null) ? (string)($row[$map['periode']] ?? '') : '',
                    'tahun'          => ($map['tahun'] !== null) ? (string)($row[$map['tahun']] ?? '') : '',
                ];

                $skp = $this->skpRepository->updateOrCreateSkp(
                    [
                        'nip' => $dataSkp['nip'], 
                        'jabatan' => $dataSkp['jabatan'],
                        'ppk' => $dataSkp['ppk'],
                    ], $dataSkp
                );

                $dataPenyusunan = [
                    'status_skp' => ($map['status_skp'] !== null) ? (string)($row[$map['status_skp']] ?? '') : '',
                ];

                $this->skpPenyusunanRepository->updateOrCreate(
                    ['skp_id' => $skp->id],
                    $dataPenyusunan
                );
            }
            return redirect()->back()->with('success', 'Data Penyusunan SKP berhasil diimport!');
        }catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $skpPenyusunan = $this->skpPenyusunanRepository->find($id);
        $skpPenyusunan->load('masterSkp');

        $listUnor = UnitOrganisasiEnum::cases();

        $listUnker = [];
        foreach ($listUnor as $unor) {
            $listUnker[$unor->value] = $unor->getUnitKerja();
        }

        return view('edit_penyusunan', compact('skpPenyusunan', 'listUnor', 'listUnker'));
    }

    public function update(Request $request, int $idPenyusunan)
    {
        $request->validate([
            'unit_organisasi' => 'required|string',
            'unit_kerja' => 'required|string',
        ]);

        $data = $request->only(['unit_organisasi', 'unit_kerja']);
        $idSkp = $this->skpPenyusunanRepository->find($idPenyusunan)->skp_id;

        $this->skpRepository->update($data, $idSkp);

        return redirect()->route('penyusunan.index')->with('success', 'Data Penyusunan SKP berhasil diperbarui!');
    }
}
