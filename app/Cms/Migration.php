<?php

namespace App\Cms;

use Illuminate\Database\Migrations\Migration as LaravelMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Str;

class Migration extends LaravelMigration
{
    // Models of migration tables
    public array $modelNameSlugs = [];

    // For $update true, if table exist it will update, if false will rebuild
    public bool $update = true;

    // Create backup before update or rebuild
    public bool $backup = false;

    private $migrationModels = [];

    public function __construct()
    {
        if ($this->modelNameSlugs === [])
        {
            $this->modelNameSlugs = config('cms.migration');
        }

        foreach($this->modelNameSlugs as $modelNameSlug)
        {
            $modelName = Str::studly($modelNameSlug);
            $modelNamespace = config('cms.config.models_namespace') . $modelName;
            $modelRepository = new $modelNamespace();
            $modelColumns = $modelRepository->getColumns();
            $modelTable = $modelRepository->getTable();
            $this->migrationModels[] = (object) [
                'modelRepository' => $modelRepository,
                'modelColumns' => $modelColumns,
                'modelTable' => $modelTable,
            ];
        }
    }

    public function up()
    {
        // If you are using mariaDB you need this.
        Schema::defaultStringLength(191); 
        foreach($this->migrationModels as $migrationModel)
        {
            if(Schema::hasTable($migrationModel->modelTable) === false)
            {
                echo 'creating ' . $migrationModel->modelTable;
                $this->createMigration($migrationModel->modelTable, $migrationModel->modelColumns);
            }
            else
            {
                if ($this->backup === true)
                {
                    echo 'backuping ' . $migrationModel->modelTable;
                    $this->createBackupTable($migrationModel->modelTable, $migrationModel->modelRepository);
                }

                if ($this->update === true)
                {
                    echo 'updating ' . $migrationModel->modelTable;
                    $this->updateMigration($migrationModel->modelTable, $migrationModel->modelColumns);
                }
                else
                {
                    echo 'rebuilding ' . $migrationModel->modelTable;
                    $this->dropTable($migrationModel->modelTable);
                    $this->createMigration($migrationModel->modelTable, $migrationModel->modelColumns);
                }
            }
            echo "\n";
        }
    }

    public function down()
    {
        $reversedmigrationModels = collect($this->migrationModels)->reverse();
        foreach($reversedmigrationModels as $migrationModel)
        {
            $this->dropTable($migrationModel->modelTable);
        }
    }

    private function dropTable($modelTable)
    {
        Schema::dropIfExists($modelTable);
    }

    private function createMigration($modelTable, $modelColumns)
    {
        Schema::create($modelTable, function (Blueprint $table) use ($modelColumns) {
            $table->bigIncrements('id');
            foreach($modelColumns as $column){
                $name = $column['name'];
                $type = isset($column['type']) ? $column['type'] : '';
                $database = isset($column['database']) ? $column['database'] : '';
                $relation = isset($column['relation']) ? $column['relation'] : '';
                if($database === 'none'){
                    continue;
                }
                $table->{$type}($name)->{$database}(true);
                if($relation){
                    $table->foreign($name)->references('id')->on($relation);
                }
            }
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function updateMigration($modelTable, $modelColumns)
    {
        $oldDatabaseColumns = Schema::getColumnListing($modelTable);
        $extraColumns = ['id', 'created_at', 'updated_at', 'deleted_at'];
        $dropColumns = $oldDatabaseColumns;
        $addColumns = collect($modelColumns)->where('database', '!=', 'none')->toArray();
        foreach($oldDatabaseColumns as $columnKey => $oldDatabaseColumn){
            $arrayIndex = array_search($oldDatabaseColumn, collect($modelColumns)->pluck('name')->toArray(), true);
            if($arrayIndex !== false){
                unset($dropColumns[$columnKey]);
                unset($addColumns[$arrayIndex]);
            }
            if(array_search($oldDatabaseColumn, $extraColumns, true) !== false){
                unset($dropColumns[$columnKey]);
            }
        }
        echo ' droping ' . count($dropColumns) . ' columns. ';
        echo 'adding ' . count($addColumns) . ' columns.';
        Schema::table($modelTable, function (Blueprint $table) use ($addColumns, $dropColumns) {
            foreach($dropColumns as $dropColumn){
                if(strpos($dropColumn, '_id') !== false){
                    $table->dropForeign([$dropColumn]);
                }
                $table->dropColumn($dropColumn);
            }
            foreach($addColumns as $column){
                $name = $column['name'];
                $type = $column['type'];
                $database = isset($column['database']) ? $column['database'] : '';
                $relation = isset($column['relation']) ? $column['relation'] : '';
                if($database === 'none'){
                    continue;
                }
                $table->{$type}($name)->{$database}(true)->after('id');
                if($relation){
                    $table->foreign($name)->references('id')->on($relation);
                }
            }
        });
    }

    private function createBackupTable($modelTable, $modelRepository)
    {
        $modelRepositoryList = $modelRepository->withTrashed()
            ->get()
            ->makeVisible('deleted_at')
            ->toArray();
        $backupTableName = $modelTable . '_backup_' . strtotime('now');
        Schema::create($backupTableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('row_data')->nullabe();
            $table->timestamps();
            $table->softDeletes();
        });
        foreach($modelRepositoryList as $modelRepositoryItem){
            \DB::table($backupTableName)->insert([
                'id' => $modelRepositoryItem['id'],
                'created_at' => $modelRepositoryItem['created_at'],
                'updated_at' => $modelRepositoryItem['updated_at'],
                'deleted_at' => $modelRepositoryItem['deleted_at'],
                'row_data' => serialize($modelRepositoryItem),
            ]);
        }
    }
}
