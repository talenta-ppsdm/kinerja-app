<?php
    
namespace App\Repositories;

use App\Enums\UnitOrganisasiEnum;
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

    public function skpPenyusunanFilter(
        ?string $unitOrganisasi, 
        ?string $untiKerja, 
        ?string $statusSkp,
        ?string $eselon,
        ?string $search,
    )
    {
        $query = $this->model->with('masterSkp');

        if ($unitOrganisasi) {
            if ($unitOrganisasi !== "lainnya") {
                $query->whereHas('masterSkp', function($q) use ($unitOrganisasi) {
                    $q->where('unit_organisasi', $unitOrganisasi);
                });
            }else{
                $query->whereHas('masterSkp', function($q) {
                    $q->whereNotIn('unit_organisasi', array_column(UnitOrganisasiEnum::cases(), 'value'));
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

        if ($eselon) {
            if ($eselon === 'non') {
                $query->whereHas('masterSkp', function($q) {
                    $q->where('eselon', 'Non Eselon');
                });
            } else {
                $query->whereHas('masterSkp', function($q) use ($eselon) {
                    $q->where('eselon', 'REGEXP', '^' . $eselon . '([^I]|$)');
                });
            }
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
}