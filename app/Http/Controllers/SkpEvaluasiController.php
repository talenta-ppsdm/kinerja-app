<?php

namespace App\Http\Controllers;

use App\Actions\ImportEvaluasi;
use App\Enums\PredicateEnum;
use App\Enums\UnitOrganisasiEnum;
use App\Models\SkpEvaluasi;
use App\Repositories\SkpEvaluasiRepository;
use App\Repositories\SkpRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class SkpEvaluasiController extends Controller
{
    protected SkpEvaluasiRepository $skpEvaluasiRepository;
    protected SkpRepository $skpRepository;

    public function __construct(SkpEvaluasiRepository $skpEvaluasiRepository, SkpRepository $skpRepository)
    {
        $this->skpEvaluasiRepository = $skpEvaluasiRepository;
        $this->skpRepository = $skpRepository;
    }

    public function index(Request $request)
    {
        $listUnor = UnitOrganisasiEnum::cases();
        
        // Showing unker filter while choosing unit organisasi
        $listUnker = [];
        foreach ($listUnor as $unor) {
            $listUnker[$unor->value] = $unor->getUnitKerja();
        }
        // TODO: Filter golongan

        $listPredicate = array_column(PredicateEnum::cases(), 'value');
        $skpEvaluasi = $this->skpEvaluasiRepository->skpEvaluasiFilter(
            $request->input('unit_organisasi'),
            $request->input('unit_kerja'),
            $request->input('predikat'),
            $request->input('triwulan'),
            $request->input('search'),
        );

        // Rekapitulasi predikat
        if ($request->filled('triwulan')) {
            $currentTriwulan = $request->input('triwulan');
        }else {
            $currentTriwulan = ceil(now()->month / 3);
        }

        $list_predikat = ['Sangat Baik', 'Baik', 'Butuh Perbaikan', 'Kurang', 'Sangat Kurang'];
        $rekap_predikat = [];

        foreach ($list_predikat as $p) {
            $key = Str::slug($p, '_');
            $rekap_predikat[$key] = $skpEvaluasi->where("predikat_tw{$currentTriwulan}", $p);
        }

        return view('monitoring_evaluasi', compact(
            'skpEvaluasi', 
            'rekap_predikat', 
            'listUnor',
            'listUnker',
            'listPredicate',
            'currentTriwulan',
        ));
    }

    public function import(Request $request, ImportEvaluasi $importEvaluasi)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ]);

        try{
            // Load excel file
            $spreadsheet = IOFactory::load($request->file('file_excel')->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Normalisasi Header agar tidak case-sensitive
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

            // Mapping Isi Header ke Database
            $map = [
                'nama'           => $findColumn(['nama']),
                'nip'            => $findColumn(['nip']),
                'status_pegawai' => $findColumn(['status pegawai']),
                'jabatan'        => $findColumn(['jabatan']),
                'golongan'       => $findColumn(['golongan']),
                'unit_kerja'     => $findColumn(['unit', 'unit (pegawai / skp)']),
                'eselon'         => $findColumn(['eselon']),
                'tagging_atasan' => $findColumn(['tagging atasan (struktur)']),
                'ppk'            => $findColumn(['pejabat penilai kinerja (skp)']),
                'periode'        => $findColumn(['periode skp']),
                'tahun'          => $findColumn(['tahun skp']),
                'predikat_tw1'   => $findColumn(['triwulan1','Triwulan I']),
                'predikat_tw2'   => $findColumn(['triwulan2','Triwulan II']),
                'predikat_tw3'   => $findColumn(['triwulan3','Triwulan III']),
                'predikat_tw4'   => $findColumn(['triwulan4','Triwulan IV']),
            ];

            // Variabel untuk menampung data pegawai terakhir (untuk baris kosong/merged)
            $lastData = [
                'nama' => '',
                'nip'  => '',
            ];

            // 3. Proses Baris Data
            foreach (array_slice($rows, 1) as $row) {
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
            
                $dataEvaluasi = [
                    'predikat_tw1' => ($map['predikat_tw1'] !== null) ? (string)($row[$map['predikat_tw1']] ?? '') : '',
                    'predikat_tw2' => ($map['predikat_tw2'] !== null) ? (string)($row[$map['predikat_tw2']] ?? '') : '',
                    'predikat_tw3' => ($map['predikat_tw3'] !== null) ? (string)($row[$map['predikat_tw3']] ?? '') : '',
                    'predikat_tw4' => ($map['predikat_tw4'] !== null) ? (string)($row[$map['predikat_tw4']] ?? '') : '',
                    'skp_id'       => $skp->id,
                ];

                $this->skpEvaluasiRepository->updateOrCreate(
                    ['skp_id' => $skp->id],
                    $dataEvaluasi
                );
            }
            
            return redirect()->back()->with('success', 'Data Evaluasi SKP berhasil diimport!');
        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        $skpEvaluasi = $this->skpEvaluasiRepository->find($id);
        $skpEvaluasi->load('masterSkp');

        $listUnor = UnitOrganisasiEnum::cases();

        // Showing unker filter while choosing unit organisasi
        $listUnker = [];
        foreach ($listUnor as $unor) {
            $listUnker[$unor->value] = $unor->getUnitKerja();
        }
        return view('edit_evaluasi', compact('skpEvaluasi', 'listUnor', 'listUnker'));
    }

    public function update(Request $request, int $idEvaluasi)
    {
        $request->validate([
            'unit_organisasi' => 'required|string',
            'unit_kerja' => 'required|string',
        ]);

        $data = $request->only(['unit_organisasi', 'unit_kerja']);
        $idSkp = $this->skpEvaluasiRepository->find($idEvaluasi)->skp_id;

        $this->skpRepository->update($data, $idSkp);

        return redirect()->route('evaluasi.index')->with('success', 'Data Evaluasi SKP berhasil diperbarui!');
    }
}
