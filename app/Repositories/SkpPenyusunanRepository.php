<?php
    
namespace App\Repositories;
    
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\{SkpPenyusunan};
    
class SkpPenyusunanRepository extends BaseRepository
{
    /**
     * Specify the model class name.
     *
     * @return string
     */
    public function model()
    {
        return SkpPenyusunan::class;
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

    public function skpPenyusunanFilter(?string $unitOrganisasi, ?string $untiKerja, ?string $statusSkp)
    {
        $query = $this->model->with('masterSkp');

        if ($unitOrganisasi) {
            if ($unitOrganisasi !== "lainnya") {
                $query->whereHas('masterSkp', function($q) use ($unitOrganisasi) {
                    $q->where('unit_organisasi', $unitOrganisasi);
                });
            }else{
                $query->whereHas('masterSkp', function($q) use ($unitOrganisasi){
                    $q->where('unit_organisasi', $unitOrganisasi);
                });
            }
        }

        if ($untiKerja) {
            $query->whereHas('masterSkp', function($q) use ($untiKerja) {
                $q->where('unit_kerja', $untiKerja);
            });
        }

        if ($statusSkp) {
            $query->where('status_skp', $statusSkp);
        }

        return $query->get();
    }
}