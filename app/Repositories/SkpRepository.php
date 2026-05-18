<?php
    
namespace App\Repositories;
    
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\{Skp};
    
class SkpRepository extends BaseRepository
{
    /**
     * Specify the model class name.
     *
     * @return string
     */
    public function model()
    {
        return Skp::class;
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

    public function allSkp()
    {
        return $this->model->with('evaluasi')->get();
    }

    public function firstOrCreateSkp(array $attributes, array $values = [])
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    public function byUnitKerjaUnitOrganisasi(string $unitKerja, string $unitOrganisasi)
    {
        return $this->model->where('unit_kerja', $unitKerja)
                           ->where('unit_organisasi', $unitOrganisasi)
                           ->get();
    }

    public function byUnitKerja (string $unitKerja)
    {
        return $this->model->where('unit_kerja', $unitKerja)
                           ->get();
    }

    public function byUnitOrganisasi (string $unitOrganisasi)
    {
        return $this->model->where('unit_organisasi', $unitOrganisasi)
                            ->get();
    }
}