<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StitchDesignController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LearnerModuleController extends Controller
{
    public function dashboard(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '0413e936266e41c493dcc562d3032b00');
    }

    public function planning(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '9c7d561fed8f4db4b55196b6eb31e477');
    }

    public function progress(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, 'b55a461e46714f29bc9d56086b0b9a28');
    }

    public function progressDetail(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, '3fa4a71383a848abb40a3381435b36b9');
    }

    public function invoices(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, 'cb6c749b53054d46a17e2dd384205c31');
    }

    public function theory(Request $request): Response
    {
        return app(StitchDesignController::class)->show($request, 'b213ebbaf7ba43129d1557010886d377');
    }
}
