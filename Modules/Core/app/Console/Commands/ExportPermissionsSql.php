<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Modules\Core\Models\Module;
use Nwidart\Modules\Facades\Module as ModuleFacade;
use Spatie\Permission\Models\Permission;

class ExportPermissionsSql extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cores:export-permissions-sql {--file=permissions.sql : The output file path}';

    /**
     * The console command description.
     */
    protected $description = 'Export missing permissions to a SQL file to bypass sequence permission issues.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->option('file');
        $modules = Module::all();
        $sql = [];

        $this->info('Analyzing permissions...');

        // Add a header to the SQL file
        $sql[] = '-- Auto-generated permissions export';
        $sql[] = '-- Run this script with a privileged database user (e.g., postgres)';
        $sql[] = "-- Command: psql -U postgres -d Inventis -f {$file}";
        $sql[] = '';

        // Suggestion to fix query
        $sql[] = '-- Fix sequence permission (Optional but recommended)';
        $sql[] = '-- GRANT USAGE, SELECT ON SEQUENCE permissions_id_seq TO "your_app_user";';
        $sql[] = '';

        foreach ($modules as $module) {
            $moduleSlug = $module->slug;
            $moduleInfo = ModuleFacade::find($moduleSlug);

            if (! $moduleInfo) {
                continue;
            }

            $configPath = $moduleInfo->getPath().'/config/permissions.php';

            if (! file_exists($configPath)) {
                continue;
            }

            $permissionsConfig = require $configPath;

            foreach ($permissionsConfig as $name => $label) {
                // Check if permission exists
                if (Permission::where('name', $name)->exists()) {
                    continue;
                }

                $category = $this->extractCategory($name);
                $guard = 'web';
                $moduleName = strtolower($moduleSlug);
                $escapedLabel = str_replace("'", "''", $label);
                $now = now()->format('Y-m-d H:i:s');

                // Generate INSERT statement
                $sql[] = 'INSERT INTO permissions (name, guard_name, module, label, description, category, created_at, updated_at) '.
                         "VALUES ('{$name}', '{$guard}', '{$moduleName}', '{$escapedLabel}', '{$escapedLabel}', '{$category}', '{$now}', '{$now}') ".
                         'ON CONFLICT (name, guard_name) DO NOTHING;';
            }
        }

        if (count($sql) <= 5) { // Only headers generated
            $this->info('All permissions seem to exist already.');

            return;
        }

        // Write to file
        file_put_contents($file, implode("\n", $sql));

        $this->info('SQL script generated successfully at: '.realpath($file));
        $this->info('Please run this SQL script using your database administrator account.');
    }

    protected function extractCategory(string $permissionName): string
    {
        if (str_contains($permissionName, '.view')) {
            return 'view';
        }
        if (str_contains($permissionName, '.index')) {
            return 'view';
        }
        if (str_contains($permissionName, '.create')) {
            return 'create';
        }
        if (str_contains($permissionName, '.store')) {
            return 'create';
        }
        if (str_contains($permissionName, '.edit')) {
            return 'edit';
        }
        if (str_contains($permissionName, '.update')) {
            return 'edit';
        }
        if (str_contains($permissionName, '.delete')) {
            return 'delete';
        }
        if (str_contains($permissionName, '.destroy')) {
            return 'delete';
        }
        if (str_contains($permissionName, '.toggle')) {
            return 'toggle';
        }
        if (str_contains($permissionName, '.show')) {
            return 'view';
        }
        if (str_contains($permissionName, '.manage')) {
            return 'manage';
        }
        if (str_contains($permissionName, '.assign')) {
            return 'assign';
        }
        if (str_contains($permissionName, '.configure')) {
            return 'configure';
        }
        if (str_contains($permissionName, '.enable')) {
            return 'enable';
        }
        if (str_contains($permissionName, '.disable')) {
            return 'disable';
        }
        if (str_contains($permissionName, '.install')) {
            return 'install';
        }
        if (str_contains($permissionName, '.uninstall')) {
            return 'uninstall';
        }

        return 'other';
    }
}
