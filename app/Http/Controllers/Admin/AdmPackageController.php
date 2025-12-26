<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafariPackage;
use App\Enums\PackageCategory;
use App\Enums\PackageType;
use App\Enums\Currency;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use  Throwable;
use Illuminate\Http\Request;

class AdmPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = SafariPackage::query();

        // 1. KEYWORD SEARCH (Mombasa, etc.)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // 2. FILTER BY TYPE (Local/International)
        if ($request->filled('type') && in_array($request->type, PackageType::values())) {
            $query->where('type', $request->type);
        }

        // 3. FILTER BY CATEGORY (Safari/Getaway/Destination)
        if ($request->filled('category') && in_array($request->category, PackageCategory::values())) {
            $query->where('category', $request->category);
        }

        $packages = $query->latest()->paginate(20);

        // Pass Enums to the view for filter dropdowns
        $types = PackageType::cases();
        $categories = PackageCategory::cases();

        return view('admin.packages.index', compact('packages', 'types', 'categories'));
    }

    public function create()
{
    $types = PackageType::cases();
    $categories = PackageCategory::cases();
    $currencies = Currency::cases();

    return view('admin.packages.create', compact('types', 'categories', 'currencies'));
}

   

    public function store(Request $request)
    {
        // 1. VALIDATION: Ensure required data is present
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:safari_packages,slug',
            'type' => 'required',
            'category' => 'required',
            'location' => 'required|string',
            'duration' => 'required|string',
            'starting_price' => 'required|integer', 
            'currency' => 'required',
            'featured_image' => 'nullable|image|max:10240', 
            'other_inclusions' => 'nullable|string', // Added
            'exclusions' => 'nullable|string', 
           
            // We only validate the type if present.
            'includes_flight' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'includes_sgr' => 'nullable|boolean',
            'includes_bus_transport' => 'nullable|boolean',
            'includes_hotel' => 'nullable|boolean',
            'includes_drinks' =>'nullable|boolean',
            'includes_tour_guide' => 'nullable|boolean',
            'includes_excursions' =>'nullable|boolean',
            'is_special_offer' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',

            
        ]);

        // 2. IMAGE UPLOAD
        $imagePath = null;
        try {
            DB::transaction(function () use ($request, $validated, &$imagePath) {
                 if ($request->hasFile('featured_image')) {
                 $imagePath = $request->file('featured_image')->store('packages', 'public');
             }

        // 3. DATA PREPARATION
            $data = $request->except(['_token', 'featured_image']);
            $data['starting_price'] = $request->starting_price * 100; 

        // Handle the check box fields (Laravel automatically sets unchecked boxes to null or doesn't include them).
        // We manually ensure all boolean fields are set to false if they were not checked (not present in $request).
            $booleanFields = [
                    'includes_flight', 'includes_sgr', 'includes_bus_transport', 'includes_hotel',
                    'includes_tour_guide', 'includes_excursions', 'includes_drinks',
                    'is_featured', 'is_special_offer',
                ];

        foreach ($booleanFields as $field) {
            $data[$field] = $request->boolean($field); 
        }

        $data['is_active'] = true;
        $data['featured_image_path'] = $imagePath;

        // 4. CREATE PACKAGE
        SafariPackage::create($data);
    });

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully!');
        } catch (Throwable $e) {
        // 4. CLEANUP: If the image uploaded but the DB failed, delete the file.
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }
        logger()->error("Package creation failed: " . $e->getMessage());
        return back()->withInput()->with('error', 'Failed to create package. Please review the details.');
    }
    }

    public function edit(SafariPackage $package)
    {
    $types = PackageType::cases();
    $categories = PackageCategory::cases();
    $currencies = Currency::cases();

    return view('admin.packages.edit', compact('package', 'types', 'categories', 'currencies'));
    }
    public function update(Request $request, SafariPackage $package)
    {
        // 1. Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:safari_packages,slug,' . $package->id,
            'type' => 'required',
            'category' => 'required',
            'location' => 'required|string',
            'duration' => 'required|string',
            'starting_price' => 'required|integer', 
            'currency' => 'required',
            'featured_image' => 'nullable|image|max:10240', 
            'other_inclusions' => 'nullable|string', 
            'exclusions' => 'nullable|string', 
            // Boolean fields validation
            'includes_flight' => 'nullable|boolean',
            'includes_sgr' => 'nullable|boolean',
            'includes_bus_transport' => 'nullable|boolean',
            'includes_hotel' => 'nullable|boolean',
            'includes_tour_guide' => 'nullable|boolean',
            'includes_excursions' => 'nullable|boolean',
            'includes_drinks' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_special_offer' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);
        
        
        $newImagePath = null;
        $oldImagePath = $package->featured_image_path;

        try {
            // Start the atomic transaction
            DB::transaction(function () use ($request, $package, $oldImagePath, &$newImagePath) {
                
                $data = $request->except(['_token', '_method', 'featured_image']);
                $data['starting_price'] = $request->starting_price * 100;
                
                // 2. Handle Image Replacement/Upload
                if ($request->hasFile('featured_image')) {
                    // Upload the new image first
                    $newImagePath = $request->file('featured_image')->store('packages', 'public');
                    $data['featured_image_path'] = $newImagePath;
                }

                // 3. Handle Boolean Checkboxes (This must happen before the update)
                $booleanFields = [
                    'includes_flight', 'includes_sgr', 'includes_bus_transport', 'includes_hotel',
                    'includes_tour_guide', 'includes_excursions', 'includes_drinks',
                    'is_featured', 'is_special_offer' 
                ];

                foreach ($booleanFields as $field) {
                    $data[$field] = $request->boolean($field);
                }

               
                // 4. Update Database Record (Throws exception if validation/DB fails)
                $package->update($data);

                // 5. CLEANUP ON SUCCESS: Only delete the OLD image after the DB update is successful.
                if ($newImagePath && $oldImagePath) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            });

            return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully!');

        } catch (Throwable $e) {
            
           
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            logger()->error("Package update failed for ID {$package->id}: " . $e->getMessage());
            
            // Return an error message, preserving user input
            return back()->withInput()->with('error', 'Failed to update package. The previous data was preserved and any new image was discarded.');
        }
    }
    


    

    public function toggleStatus(SafariPackage $package)
    {
        // 1. Flip the status
        $newState = !$package->is_active;
        
        // 2. Update just this one column
        $package->update(['is_active' => $newState]);

        // 3. Feedback
        $statusMessage = $newState ? 'restored and visible' : 'archived and hidden';
        
        return back()->with('success', "Package successfully $statusMessage.");
    }
}