<?php

namespace App\Http\Controllers;

use App\Repositories\SkpPenyusunanRepository;
use Illuminate\Http\Request;

class SkpPenyusunanController extends Controller
{
    protected SkpPenyusunanRepository $skpPenyusunanRepository;

    public function __construct(SkpPenyusunanRepository $skpPenyusunanRepository)
    {
        $this->skpPenyusunanRepository = $skpPenyusunanRepository;
    }

    public function index()
    {
        $skpPenyusunan = $this->skpPenyusunanRepository->allPenyusunanWithSkp();
        return view('monitoring_penyusunan', compact('skpPenyusunan'));
    }
}
