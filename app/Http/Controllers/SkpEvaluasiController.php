<?php

namespace App\Http\Controllers;

use App\Actions\ImportEvaluasi;
use App\Enums\PredicateEnum;
use App\Enums\UnitOrganisasiEnum;
use App\Models\SkpEvaluasi;
use App\Repositories\MasterKriteriaRepository;
use App\Repositories\SkpEvaluasiRepository;
use App\Repositories\SkpRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SkpEvaluasiController extends Controller
{
    protected SkpEvaluasiRepository $skpEvaluasiRepository;
    protected SkpRepository $skpRepository;
    protected MasterKriteriaRepository $kriteriaRepository;

    public function __construct(
        SkpEvaluasiRepository $skpEvaluasiRepository, 
        SkpRepository $skpRepository,
        MasterKriteriaRepository $kriteriaRepository,
    )
    {
        $this->skpEvaluasiRepository = $skpEvaluasiRepository;
        $this->skpRepository = $skpRepository;
        $this->kriteriaRepository = $kriteriaRepository;
    }

    public function index(Request $request)
    {
        $listUnor = UnitOrganisasiEnum::cases();
        
        // Showing unker filter while choosing unit organisasi
        $listUnker = [];
        foreach ($listUnor as $unor) {
            $listUnker[$unor->value] = $unor->getUnitKerja();
        }

        $listPredicate = array_column(PredicateEnum::cases(), 'value');
        $skpEvaluasi = $this->skpEvaluasiRepository->skpEvaluasiFilter(
            $request->input('unit_organisasi'),
            $request->input('unit_kerja'),
            $request->input('predikat'),
            $request->input('triwulan'),
            $request->input('eselon'),
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

        // Handle triwulan column
        $skpEvaluasi = $skpEvaluasi->map(function ($item) {
            $periode = $item->masterSkp->periode ?? null;
            
            $twAwal = 0;
            $twAkhir = 0;

            if ($periode) {
                try {
                    $startDateString = substr($periode, 0, 10);
                    $twAwal = Carbon::createFromFormat('d-m-Y', $startDateString)->quarter;

                    $endDateString = substr($periode, -10);
                    $twAkhir = Carbon::createFromFormat('d-m-Y', $endDateString)->quarter;
                } catch (\Exception $e) {
                    $twAwal = 0;
                    $twAkhir = 0;
                }
            }

            $item->tw_awal = $twAwal;
            $item->tw_akhir = $twAkhir;
            
            return $item;
        });

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
                'no'             => $findColumn(['no']),
                'nama'           => $findColumn(['nama']),
                'nip'            => $findColumn(['nip']),
                'status_pegawai' => $findColumn(['status pegawai']),
                'jabatan'        => $findColumn(['jabatan']),
                'golongan'       => $findColumn(['golongan']),
                'unit_kerja'     => $findColumn(['unit', 'unit (pegawai / skp)', 'unit (pegawai/skp)']),
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
                        'periode' => $dataSkp['periode'],
                        'tahun' => $dataSkp['tahun'],
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

   public function export(Request $request)
   {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Header - Include semua 4 triwulan
        $headers = [
            'No', 
            'Nama', 
            'NIP', 
            'Status Pegawai', 'Golongan', 'Jabatan', 
            'Unit (Pegawai/SKP)', 'Eselon', 'Tagging Atasan (Struktur)', 
            'Pejabat Penilai Kinerja (SKP)', 'Tahun SKP', 'Periode SKP', 
            'Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV',
            'Keterangan', 'Keterangan Tambahan', 'Keterangan Perpindahan', 'Bukti Pendukung'
        ];
        $sheet->fromArray($headers, NULL, 'A1');

        // 2. Ambil Data Group By NIP dengan menerapkan filter yang sama seperti index
        $dataEvaluasiRaw = $this->skpEvaluasiRepository->skpEvaluasiFilter(
            $request->input('unit_organisasi'),
            $request->input('unit_kerja'),
            null,  // No predikat filter
            null,  // No triwulan filter for filtering
            $request->input('eselon'),
            $request->input('search'),
        );

        $masterKriteria = $this->kriteriaRepository->all()->pluck('nama_kriteria')->toArray();
        $jumlahKriteria = count($masterKriteria);
        if ($jumlahKriteria === 0) {
            $jumlahKriteria = 1;
            $masterKriteria = ['Kriteria'];
        }
        
        // Validasi data
        if ($dataEvaluasiRaw->isEmpty()) {
            $sheet->setCellValue('A2', 'Tidak ada data sesuai filter');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Export_SKP.xlsx"');
            $writer->save('php://output');
            exit;
        }

        // Map data untuk menambahkan nip attribute, kemudian group by nip
        $dataEvaluasi = $dataEvaluasiRaw->map(function($item) {
            $item->nip = $item->masterSkp->nip;
            return $item;
        })->groupBy('nip'); 
        
        $currentRow = 2;
        $no = 1;

        foreach ($dataEvaluasi as $nip => $records) {
            $startGrup = $currentRow;
            $totalBarisGrup = $records->count() * $jumlahKriteria;
            $endGrup = $currentRow + ($totalBarisGrup - 1);
            
            // Merge nama dan nip untuk semua baris pegawai ini
            if ($totalBarisGrup > 1) {
                $sheet->mergeCells("A{$startGrup}:A{$endGrup}"); // No
                $sheet->mergeCells("B{$startGrup}:B{$endGrup}"); // Nama
                $sheet->mergeCells("C{$startGrup}:C{$endGrup}"); // NIP
            }
            
            $first = $records->first();
            $sheet->setCellValue('A' . $startGrup, $no++);
            $sheet->setCellValue('B' . $startGrup, $first->masterSkp->nama);
            $sheet->setCellValue('C' . $startGrup, $first->masterSkp->nip);

            foreach ($records as $record) {
                $startEval = $currentRow;
                $endEval = $currentRow + ($jumlahKriteria - 1);

                // Merge data evaluasi (D-T) untuk semua kriteria baris
                $colsEval = ['D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T'];
                foreach ($colsEval as $col) {
                    if ($jumlahKriteria > 1) {
                        $sheet->mergeCells("{$col}{$startEval}:{$col}{$endEval}");
                    }
                }

                // Set data evaluasi di row pertama saja (akan ter-merge ke bawah)
                $sheet->setCellValue('D' . $startEval, $record->masterSkp->status_pegawai);
                $sheet->setCellValue('E' . $startEval, $record->masterSkp->golongan);
                $sheet->setCellValue('F' . $startEval, $record->masterSkp->jabatan);
                $sheet->setCellValue('G' . $startEval, $record->masterSkp->unit_kerja);
                $sheet->setCellValue('H' . $startEval, $record->masterSkp->eselon);
                $sheet->setCellValue('I' . $startEval, $record->masterSkp->tagging_atasan);
                $sheet->setCellValue('J' . $startEval, $record->masterSkp->ppk);
                $sheet->setCellValue('K' . $startEval, $record->masterSkp->tahun);
                $sheet->setCellValue('L' . $startEval, $record->masterSkp->periode);
                
                // Semua 4 triwulan
                $sheet->setCellValue('M' . $startEval, $record->predikat_tw1);
                $sheet->setCellValue('N' . $startEval, $record->predikat_tw2);
                $sheet->setCellValue('O' . $startEval, $record->predikat_tw3);
                $sheet->setCellValue('P' . $startEval, $record->predikat_tw4);
                
                $sheet->setCellValue('R' . $startEval, $record->keterangan_tambahan);
                $sheet->setCellValue('S' . $startEval, $record->keterangan_perpindahan);
                $sheet->setCellValue('T' . $startEval, $record->bukti_dukung);

                // Loop kriteria - setiap kriteria di baris terpisah
                foreach ($masterKriteria as $i => $kriteria) {
                    $rowKriteria = $startEval + $i;
                    $sheet->setCellValue('Q' . $rowKriteria, $kriteria);
                }

                $currentRow = $endEval + 1;
            }
        }

        // 3. Styling
        $sheet->getStyle("A1:T" . ($currentRow - 1))
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle("A1:T" . ($currentRow - 1))
            ->getAlignment()->setWrapText(true);
        
        // Styling untuk header row
        $sheet->getStyle('A1:T1')->getFont()->setBold(true);
        $sheet->getStyle('A1:T1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3');
            
        foreach (range('A', 'T') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getStyle('A1:T' . ($currentRow - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_SKP_All_Triwulan.xlsx"');
        $writer->save('php://output');
        exit;
   }

   Public function deleteAll()
   {
        try {
            $this->skpEvaluasiRepository->deleteAll();
            return redirect()->back()->with('success', 'Semua data evaluasi SKP berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Delete All Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus semua data evaluasi SKP: ' . $e->getMessage());
        }
   }
}
