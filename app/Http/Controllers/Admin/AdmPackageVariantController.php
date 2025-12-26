<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\SafariPackage;
use App\Models\PackageVariant;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Http\Request;

class AdmPackageVariantController extends Controller
{
    
    

    /**
     * 2. CREATE: Shows the form to create a new variant.
     * Route: GET /admin/packages/{package}/variants/create
     */
    public function create(SafariPackage $package)
    {
        // Ensure the parent package object is available in the form
        return view('admin.variants.create', compact('package'));
    }

    /**
     * 3. STORE: Saves the new variant record to the database.
     * Route: POST /admin/packages/{package}/variants
     */
    
        public function store(Request $request, SafariPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string', 
            'inclusions' => 'nullable|string',
            'featured_image' => 'nullable|image|max:10240',
        ]);

        $imagePath = null;
        
        try {
            DB::transaction(function () use ($request, $package, $validated, &$imagePath) {
                
                
                if ($request->hasFile('featured_image')) {
                    $imagePath = $request->file('featured_image')->store('variants', 'public');
                }

                // 2. Prepare Data
                $data = $validated;
                
                $data['price'] = $request->price * 100; 
                $data['featured_image_path'] = $imagePath;
                $data['safari_package_id'] = $package->id; 

                
                PackageVariant::create($data);
            });

            return redirect()->route('admin.packages.edit', $package)
                             ->with('success', 'Package variant created successfully!');

        } catch (Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            
            logger()->error("Variant creation failed for package ID {$package->id}: " . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to create package variant. Please check the form and try again.');
        }
    }

    
    

    /**
     * 5. EDIT: Shows the form to edit an existing variant.
     * Route: GET /admin/packages/{package}/variants/{variant}/edit
     */
    public function edit(SafariPackage $package, PackageVariant $variant)
    {
        return view('admin.variants.edit', compact('package', 'variant'));
    }

    /**
     * 6. UPDATE: Updates the variant record in the database.
     * 
     */
    public function update(Request $request, SafariPackage $package, PackageVariant $variant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string', 
            'inclusions' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $newImagePath = null;
        $oldImagePath = $variant->featured_image_path;

        try {
            DB::transaction(function () use ($request, $variant, $validated, $oldImagePath, &$newImagePath) {
                
                // 1. Prepare Data
                $data = $validated;
                $data['price'] = $request->price * 100; // Convert to cents

                // 2. Handle Image Change
                if ($request->hasFile('featured_image')) {
                    $newImagePath = $request->file('featured_image')->store('variants', 'public');
                    if ($oldImagePath) {
                        Storage::disk('public')->delete($oldImagePath);
                    }
                    $data['featured_image_path'] = $newImagePath;
                } else {
                    $data['featured_image_path'] = $oldImagePath;
                }
                
                // 3. Update Database Record
                // FIXED: We use $data (which has the cents price) instead of $validated
                $variant->update($data);
            });

            return redirect()->route('admin.packages.edit', $package)
                             ->with('success', 'Package variant updated successfully!');

        } catch (Throwable $e) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            logger()->error("Variant update failed for variant ID {$variant->id}: " . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update package variant. The previous data was preserved.');
        }
    }

    /**
     * 7. DESTROY: Deletes a variant record.
     * 
     */
    public function destroy(SafariPackage $package, PackageVariant $variant)
    {
        $imagePath = $variant->featured_image_path;
        
        try {
            DB::transaction(function () use ($variant, $imagePath) {
                
                // 1. Delete Database Record (If this fails, the transaction rolls back)
                $variant->delete();
                
                // 2. Delete Image (If this fails, an exception is thrown and the DB delete rolls back)
                if ($imagePath) {
                     Storage::disk('public')->delete($imagePath);
                }
            });

            return back()->with('success', 'Package variant deleted successfully!');

        } catch (Throwable $e) {
            // Since the entire process failed, the variant record is guaranteed to be in the database
            logger()->error("Variant deletion failed for variant ID {$variant->id}: " . $e->getMessage());
            return back()->with('error', 'Failed to delete package variant. Try again later.');
        }
    }
}

