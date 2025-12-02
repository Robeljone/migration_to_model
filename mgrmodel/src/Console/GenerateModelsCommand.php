<?php

namespace RobelJone\MgrModel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateModelsCommand extends Command
{

    protected $signature = 'autogen:models';
    protected $description = 'Generate Eloquent models from migration files';

    public function handle()
    {
        $migrationsDir = base_path('database/migrations');
        $modelsDir = app_path('Models');

        if (!is_dir($modelsDir)) 
        {
            mkdir($modelsDir, 0755, true);
        }

        $this->info("Scanning migrations...");

        foreach (glob("$migrationsDir/*.php") as $file) {
            $content = file_get_contents($file);

            // Extract table name
            preg_match("/Schema::create\('([^']+)'/", $content, $match);
            if (!isset($match[1])) continue;

            $table = $match[1];

            // Convert table name → Model name
            $model = Str::studly(Str::singular($table));

            $this->line("📌 Generating model for: $model ($table)");

            // Extract column names
            preg_match_all("/->.*?\('([^']+)'\)/", $content, $cols);

            $columns = collect($cols[1])
                ->reject(fn ($c) => in_array($c, [
                    'id','created_at','updated_at','deleted_at'
                ]));

            // Build fillable array
            $fillable = $columns->map(fn ($c) => "        '$c',")->implode("\n");

            // Create model file
            $modelPath = "$modelsDir/$model.php";
            file_put_contents($modelPath, $this->modelTemplate($model, $fillable));

            $this->info("✅ $model created!");
        }

        $this->info("🎉 All models generated!");
    }

    private function modelTemplate($model, $fillable)
    {
        return <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class $model extends Model
{
    protected \$fillable = [
$fillable
    ];
}

PHP;
    }
}
