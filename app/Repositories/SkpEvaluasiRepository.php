<?php
    
namespace App\Repositories;

use App\Enums\PredicateEnum;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\{SkpEvaluasi};
    
class SkpEvaluasiRepository extends BaseRepository
{
    /**
     * Specify the model class name.
     *
     * @return string
     */
    public function model()
    {
        return SkpEvaluasi::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        // Add your boot logic here
    }

    public function allEvaluasiWithSkp()
    {
        return $this->model->with('masterSkp')->get();
    }

    public function skpEvaluasiFilter(?string $unitOrganisasi, ?string $unitKerja, ?string $predikat, ?int $triwulan, ?string $search)
    {
        $query = $this->model->with('masterSkp');

        if ($unitOrganisasi) {
            $query->whereHas('masterSkp', function($q) use ($unitOrganisasi) {
                $q->where('unit_organisasi', $unitOrganisasi);
            });
        }

        if ($unitKerja) {
            $query->whereHas('masterSkp', function($q) use ($unitKerja) {
                $q->where('unit_kerja', $unitKerja);
            });
        }

        if ($predikat && $triwulan) {
            $column = "predikat_tw{$triwulan}";
            $query->where($column, $predikat);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('masterSkp', function($subQ) use ($search) {
                    $subQ->where('nama', 'like', "%{$search}%")
                          ->orWhere('nip', 'like', "%{$search}%")
                          ->orWhere('unit_kerja', 'like', "%{$search}%")
                          ->orWhere('jabatan', 'like', "%{$search}%");
                });
            });
        }

        return $query->get();
    }

    public function byPredikat(string $predikat, int $triwulan)
    {
        $column = "predikat_tw{$triwulan}";
        return $this->model->where($column, $predikat)->get();
    }

    public function byUnitOrganisasi(string $unitOrganisasi)
    {
        return $this->model->whereHas('masterSkp', function($query) use ($unitOrganisasi) {
            $query->where('unit_organisasi', $unitOrganisasi);
        })->get();
    }
    
    public function byUnitOrganisasiAndUnitKerja(string $unitOrganisasi, string $unitKerja)
    {
        return $this->model->whereHas('masterSkp', function($query) use ($unitOrganisasi, $unitKerja) {
            $query->where('unit_organisasi', $unitOrganisasi)
                  ->where('unit_kerja', $unitKerja);
        })->get();
    }

    public function byTriwulan(int $triwulan)
    {
        $column = "predikat_tw{$triwulan}";
        $predicate = array_column(PredicateEnum::cases(), 'value');

        return $this->model->whereIn($column, $predicate)->get();
    }

}