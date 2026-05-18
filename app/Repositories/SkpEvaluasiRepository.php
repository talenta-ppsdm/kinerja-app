<?php
    
namespace App\Repositories;
    
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
}