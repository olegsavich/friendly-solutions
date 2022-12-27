<?php

namespace App\Modules\Importer\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Importer\Repositories\ImporterRepository;
use Illuminate\Config\Repository as Config;
use App\Modules\Importer\Http\Requests\ImporterRequest;
use Illuminate\Http\Response;
use App;

/**
 * @package App\Modules\Importer\Http\Controllers
 */
class ImporterController extends Controller
{
    /**
     * @var ImporterRepository
     */
    private $importerRepository;

    /**
     * @param ImporterRepository $importerRepository
     */
    public function __construct(ImporterRepository $importerRepository)
    {
        $this->middleware('auth');
        $this->importerRepository = $importerRepository;
    }

    /**
     * @param Config $config
     *
     * @return Response
     */
    public function index(Config $config)
    {
        $this->checkPermissions(['importer.index']);
        $onPage = $config->get('system_settings.importer_pagination');
        $list = $this->importerRepository->paginate($onPage);

        return response()->json($list);
    }

    /**
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $this->checkPermissions(['importer.show']);
        $id = (int) $id;

        return response()->json($this->importerRepository->show($id));
    }

    /**
     * @return Response
     */
    public function create()
    {
        $this->checkPermissions(['importer.store']);
        $rules['fields'] = $this->importerRepository->getRequestRules();

        return response()->json($rules);
    }

    /**
     * @param ImporterRequest $request
     *
     * @return Response
     */
    public function store(ImporterRequest $request)
    {
        $this->checkPermissions(['importer.store']);
        $model = $this->importerRepository->create($request->all());

        return response()->json(['item' => $model], 201);
    }

    /**
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->checkPermissions(['importer.update']);
        $id = (int) $id;

        return response()->json($this->importerRepository->show($id, true));
    }

    /**
     * @param ImporterRequest $request
     * @param  int $id
     *
     * @return Response
     */
    public function update(ImporterRequest $request, $id)
    {
        $this->checkPermissions(['importer.update']);
        $id = (int) $id;

        $record = $this->importerRepository->updateWithIdAndInput($id,
            $request->all());

        return response()->json(['item' => $record]);
    }

    /**
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->checkPermissions(['importer.destroy']);
        App::abort(404);
        exit;
    }
}