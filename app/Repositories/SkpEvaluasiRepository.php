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

    public function byPredikat(string $predikat, int $triwulan)
    {
        $column = "predikat_tw{$triwulan}";
        return $this->model->where($column, $predikat)->get();
    }
    
    public function byUnitKerja(string $unitKerja)
    {
        return $this->model->where('unit_kerja', $unitKerja)->get();
    }
}