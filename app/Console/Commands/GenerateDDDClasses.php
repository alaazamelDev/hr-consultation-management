<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateDDDClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ddd:generate-ddd-classes {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->argument('model');

        $interfaceContent = $this->generateInterfaceContent($modelName);
        $this->generateClass('Domain/Repositories', "{$modelName}RepositoryInterface", $interfaceContent);

        $implementationContent = $this->generateImplementationContent($modelName);
        $this->generateClass('Infrastructure/Persistence/Eloquent', "Eloquent{$modelName}Repository", $implementationContent);

        $serviceContent = $this->generateServiceContent($modelName);
        $this->generateClass('Domain/Services', "{$modelName}Service", $serviceContent);

        $resourceContent = $this->generateResourceContent($modelName);
        $this->generateClass('Application/Http/Resources', "{$modelName}Resource", $resourceContent);

        $controllerContent = $this->generateControllerContent($modelName);
        $this->generateClass('Application/Http/Controllers', "{$modelName}Controller", $controllerContent);
    }
    public function generateInterfaceContent(string $modelName): string
    {
        $modelClass = "App\\Domain\\Models\\{$modelName}";

        $interfaceContent = "<?php

namespace App\Domain\Repositories;

use {$modelClass};
use Illuminate\Database\Eloquent\Builder;

interface {$modelName}RepositoryInterface
{
    public function get{$modelName}List(): array;

    public function get{$modelName}ById(int \$id): {$modelName}|Builder|null;

    public function create{$modelName}(array \$data): {$modelName}|Builder|null;

    public function update{$modelName}(int \$id, array \$data): {$modelName}|Builder|null;

    public function delete{$modelName}(\$id): {$modelName}|Builder|null;
}";

        return $interfaceContent;
    }

    protected function generateImplementationContent(string $modelName): string
    {
        $modelClass = "App\\Domain\\Models\\{$modelName}";

        $implementationContent = "<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\\{$modelName}RepositoryInterface;
use {$modelClass};
use Illuminate\Database\Eloquent\Builder;

class Eloquent{$modelName}Repository implements {$modelName}RepositoryInterface
{
    public function get{$modelName}List(): array
    {
        // TODO: Implement the logic to retrieve a list of {$modelName}s
    }

    public function get{$modelName}ById(int \$id): {$modelName}|Builder|null
    {
        // TODO: Implement the logic to retrieve a {$modelName} by ID
    }

    public function create{$modelName}(array \$data): {$modelName}|Builder|null
    {
        // TODO: Implement the logic to create a {$modelName}
    }

    public function update{$modelName}(int \$id, array \$data): {$modelName}|Builder|null
    {
        // TODO: Implement the logic to update a {$modelName}
    }

    public function delete{$modelName}(\$id): {$modelName}|Builder|null
    {
        // TODO: Implement the logic to delete a {$modelName}
    }
}";

        return $implementationContent;
    }

    protected function generateServiceContent(string $modelName): string
    {
        $modelClass = "App\\Domain\\Models\\{$modelName}";
        $repositoryInterface = "{$modelName}RepositoryInterface";

        $serviceContent = "<?php

namespace App\Domain\Services;

use App\Domain\Repositories\\{$repositoryInterface};
use {$modelClass};
use Illuminate\Database\Eloquent\Builder;

class {$modelName}Service
{
    private $repositoryInterface \${$modelName}Repository;

    public function __construct({$repositoryInterface} \${$modelName}Repository)
    {
        \$this->{$modelName}Repository = \${$modelName}Repository;
    }

    public function get{$modelName}List(): array
    {
        return \$this->{$modelName}Repository->get{$modelName}List();
    }

    public function get{$modelName}ById(int \$id): {$modelName}|Builder|null
    {
        return \$this->{$modelName}Repository->get{$modelName}ById(\$id);
    }

    public function create{$modelName}(array \$data): {$modelName}|Builder|null
    {
        return \$this->{$modelName}Repository->create{$modelName}(\$data);
    }

    public function update{$modelName}(int \$id, array \$data): {$modelName}|Builder|null
    {
        return \$this->{$modelName}Repository->update{$modelName}(\$id, \$data);
    }

    public function delete{$modelName}(\$id): {$modelName}|Builder|null
    {
        return \$this->{$modelName}Repository->delete{$modelName}(\$id);
    }
}";

        return $serviceContent;
    }

    protected function generateResourceContent(string $modelName): string
    {
        $resourceContent = "<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\\{$modelName};

class {$modelName}Resource extends JsonResource
{
    public function toArray(Request \$request): array
    {
        return parent::toArray(\$request);
    }
}";

        return $resourceContent;
    }


    protected function generateControllerContent(string $modelName): string
    {
        $resourceClass = "{$modelName}Resource";
        $serviceClass = "{$modelName}Service";

        $model_name =lcfirst($modelName);

        $controllerContent = "<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\\{$resourceClass};
use App\Domain\Services\\{$serviceClass};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class {$modelName}Controller extends Controller
{
    private $serviceClass \${$serviceClass};

    public function __construct({$serviceClass} \${$serviceClass})
    {
        \$this->{$serviceClass} = \${$serviceClass};
    }

    public function index(): JsonResponse
    {
        \${$model_name}s = \$this->{$serviceClass}->get{$modelName}List();
        return response()->json([
            'data'=>{$resourceClass}::collection(\${$model_name}s) //Modify it as needed
            ], 200);
    }

    public function show(int \$id): JsonResponse
    {
        \${$model_name} = \$this->{$serviceClass}->get{$modelName}ById(\$id);
        return response()->json([
            'data'=> new {$resourceClass}(\${$model_name}) //Modify it as needed
            ], 200);
    }

    public function store(): JsonResponse
    {
        \${$model_name} = \$this->{$serviceClass}->create{$modelName}(request()->all());
        return response()->json([
            'data'=> new {$resourceClass}(\${$model_name}) //Modify it as needed
            ], 200);
    }

    public function update(int \$id): JsonResponse
    {
        \${$model_name} = \$this->{$serviceClass}->update{$modelName}(\$id, request()->all());
        return response()->json([
            'data'=> new {$resourceClass}(\${$model_name}) //Modify it as needed
            ], 200);
    }

    public function destroy(int \$id): JsonResponse
    {
        \${$model_name} = \$this->{$serviceClass}->delete{$modelName}(\$id);
        return response()->json([
            'data'=> new {$resourceClass}(\${$model_name}) //Modify it as needed
            ], 200);
    }
}";

        return $controllerContent;
    }



    protected function generateClass(string $directory, string $className, string $content)
    {
        $path = app_path($directory . '/' . $className . '.php');
        file_put_contents($path, $content);
    }

}

