<?php

namespace App\Http\Livewire\Admin;

use App\Models\Settings;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithFileUploads;

class ThemeDisplay extends Component
{
    use WithFileUploads;
    public $theme;
    public bool $uploadTheme = false;

    public function render()
    {
        return view('livewire.admin.theme-display');
    }

    public function setTheme($theme)
    {

        Settings::where('id', '1')
            ->update([
                'website_theme' => $theme,
            ]);
    }

    public function uploadTheme(): void
    {
        $this->uploadTheme = true;
    }

    public function cancelUpload(): void
    {
        $this->uploadTheme = false;
    }


    protected $rules = [
        'theme' => ['max:30000'],
    ];

    public function saveTheme()
    {
        $settings = Settings::find(1);
        $this->validate();
        sleep(4);

        if ($this->theme->extension() != 'zip') {
            session()->flash('error', 'Please upload a zip file');
        } else {

            // Sanitize theme name — allow only alphanumeric, dash, underscore
            $themeName = preg_replace('/[^a-zA-Z0-9_\-]/', '', substr($this->theme->getClientOriginalName(), 0, -4));
            if (empty($themeName)) {
                session()->flash('error', 'Invalid theme name.');
                return redirect()->route('appsettingshow');
            }

            // read the content of the zip file
            $zip = new \ZipArchive();
            $open = $zip->open($this->theme->getRealPath());

            if ($open === TRUE) {
                // Validate every outer zip entry for path traversal before extracting
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entry = $zip->getNameIndex($i);
                    if (strpos($entry, '..') !== false || strpos($entry, "\0") !== false) {
                        $zip->close();
                        session()->flash('error', 'Invalid zip: path traversal detected.');
                        return redirect()->route('appsettingshow');
                    }
                }

                // extract the zip file to the themes folder
                $zip->extractTo(base_path("themes/{$themeName}"));
                $zip->close();

                // Safely extract the nested views.zip — only allow .blade.php files
                $viewsZipPath = base_path("themes/{$themeName}/views.zip");
                if (!file_exists($viewsZipPath)) {
                    session()->flash('error', 'Theme is missing views.zip.');
                    return redirect()->route('appsettingshow');
                }

                $viewsZip = new \ZipArchive();
                if ($viewsZip->open($viewsZipPath) === TRUE) {
                    for ($i = 0; $i < $viewsZip->numFiles; $i++) {
                        $entry = $viewsZip->getNameIndex($i);
                        if (strpos($entry, '..') !== false || strpos($entry, "\0") !== false) {
                            $viewsZip->close();
                            session()->flash('error', 'Invalid views.zip: path traversal detected.');
                            return redirect()->route('appsettingshow');
                        }
                        if (!str_ends_with($entry, '/') && !str_ends_with($entry, '.blade.php')) {
                            $viewsZip->close();
                            session()->flash('error', 'Invalid views.zip: only .blade.php files are permitted.');
                            return redirect()->route('appsettingshow');
                        }
                    }
                    $viewsZip->extractTo(resource_path('views'));
                    $viewsZip->close();
                }

                // add the theme to the database
                $settings->theme = $themeName;
                $settings->save();

                // reset the upload form
                $this->theme = null;
                session()->flash('success', 'Theme uploaded successfully');
            } else {
                session()->flash('error', 'There was an error uploading the theme, please try again.');
            }
        }
        return redirect()->route('appsettingshow');
    }

    //chose theme
    public function chooseTheme(int $id)
    {
        //set the active theme, only one theme can be active at a time
        Theme::where('active', true)->update(['active' => false]);
        Theme::where('id', $id)->update(['active' => 1]);
        session()->flash('success', 'Theme activated successfully');
    }

    //clear cache files and views
    public function clearCache()
    {
        //clear the cache with Artisan command
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        session()->flash('success', 'Cache cleared successfully');
        return redirect()->route('appsettingshow');
    }
}
