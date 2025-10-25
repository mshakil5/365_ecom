<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CompanyDetailsController;
use App\Http\Controllers\Admin\ContactMailController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SubSubCategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductModelController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\WarrantyController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ApiProductController;

Route::group(['prefix' =>'admin/', 'middleware' => ['auth', 'is_admin']], function(){
    Route::get('/dashboard', [HomeController::class, 'adminHome'])->name('admin.dashboard');
    // User CRUD
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user-update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.delete');
    Route::post('/user-status', [UserController::class, 'toggleStatus'])->name('user.toggleStatus');

    Route::get('/slider', [SliderController::class, 'getSlider'])->name('allslider');
    Route::post('/slider', [SliderController::class, 'sliderStore']);
    Route::get('/slider/{id}/edit', [SliderController::class, 'sliderEdit']);
    Route::post('/slider-update', [SliderController::class, 'sliderUpdate']);
    Route::delete('/slider/{id}', [SliderController::class, 'sliderDelete'])->name('slider.delete');
    Route::post('/slider-status', [SliderController::class, 'toggleStatus']);
    Route::post('/sliders/update-order', [SliderController::class, 'updateOrder'])->name('sliders.updateOrder');

    Route::get('/contacts', [ContactController::class,'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [ContactController::class,'show'])->name('contacts.show');
    Route::delete('/contacts/{id}/delete', [ContactController::class,'destroy'])->name('contacts.delete');
    Route::post('/contacts/toggle-status', [ContactController::class,'toggleStatus'])->name('contacts.toggleStatus');

    // FAQ
    Route::get('/faq', [FAQController::class, 'index'])->name('faq.index');
    Route::post('/faq', [FAQController::class, 'store'])->name('faq.store');
    Route::get('/faq/{id}/edit', [FAQController::class, 'edit'])->name('faq.edit');
    Route::post('/faq-update', [FAQController::class, 'update'])->name('faq.update');
    Route::delete('/faq/{id}', [FAQController::class, 'destroy'])->name('faq.delete');

    // Contacts
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [ContactController::class, 'show']);
    Route::get('/contacts/{id}/delete', [ContactController::class, 'destroy']);
    Route::post('/contacts/status', [ContactController::class, 'toggleStatus'])->name('contacts.status');

    Route::get('/company-details', [CompanyDetailsController::class, 'index'])->name('admin.companyDetails');
    Route::post('/company-details', [CompanyDetailsController::class, 'update'])->name('admin.companyDetails');

    Route::get('/company/seo-meta', [CompanyDetailsController::class, 'seoMeta'])->name('admin.company.seo-meta');
    Route::post('/company/seo-meta/update', [CompanyDetailsController::class, 'seoMetaUpdate'])->name('admin.company.seo-meta.update');

    Route::get('/about-us', [CompanyDetailsController::class, 'aboutUs'])->name('admin.aboutUs');
    Route::post('/about-us', [CompanyDetailsController::class, 'aboutUsUpdate'])->name('admin.aboutUs');

    Route::get('/privacy-policy', [CompanyDetailsController::class, 'privacyPolicy'])->name('admin.privacy-policy');
    Route::post('/privacy-policy', [CompanyDetailsController::class, 'privacyPolicyUpdate'])->name('admin.privacy-policy');

    Route::get('/terms-and-conditions', [CompanyDetailsController::class, 'termsAndConditions'])->name('admin.terms-and-conditions');
    Route::post('/terms-and-conditions', [CompanyDetailsController::class, 'termsAndConditionsUpdate'])->name('admin.terms-and-conditions');
    
    Route::get('/mail-body', [CompanyDetailsController::class, 'mailBody'])->name('admin.mail-body');
    Route::post('/mail-body', [CompanyDetailsController::class, 'mailBodyUpdate'])->name('admin.mail-body');

    Route::get('/home-footer', [CompanyDetailsController::class, 'homeFooter'])->name('admin.home-footer');
    Route::post('/home-footer', [CompanyDetailsController::class, 'homeFooterUpdate'])->name('admin.home-footer');

    Route::get('/copyright', [CompanyDetailsController::class, 'copyright'])->name('admin.copyright');
    Route::post('/copyright', [CompanyDetailsController::class, 'copyrightUpdate'])->name('admin.copyright');

    Route::get('/contact-emails', [ContactMailController::class, 'index'])->name('contactemails.index');
    Route::post('/contact-emails/store', [ContactMailController::class, 'store'])->name('contactemails.store');
    Route::get('/contact-emails/{id}/edit', [ContactMailController::class, 'edit'])->name('contactemails.edit');
    Route::post('/contact-emails/update', [ContactMailController::class, 'update'])->name('contactemails.update');
    Route::delete('/contact-emails/{id}', [ContactMailController::class, 'destroy'])->name('contactemails.destroy');

    Route::get('/master', [MasterController::class, 'index'])->name('master.index');
    Route::post('/master', [MasterController::class, 'store'])->name('master.store');
    Route::get('/master/{id}/edit', [MasterController::class, 'edit'])->name('master.edit');
    Route::post('/master-update', [MasterController::class, 'update'])->name('master.update');
    Route::delete('/master/{id}', [MasterController::class, 'destroy'])->name('master.delete');

    Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::post('/sections/update-order', [SectionController::class, 'updateOrder'])->name('sections.updateOrder');
    Route::post('/sections/toggle-status', [SectionController::class, 'toggleStatus'])->name('sections.toggleStatus');

    //Api Products
    Route::get('/api-product', [ApiProductController::class, 'index'])->name('api_products.index');
    Route::post('/api-product', [ApiProductController::class, 'store'])->name('api_products.store');
    Route::get('/api-product/{id}/edit', [ApiProductController::class, 'edit'])->name('api_products.edit');
    Route::post('/api-product-update', [ApiProductController::class, 'update'])->name('api_products.update');
    Route::delete('/api-product/{id}', [ApiProductController::class, 'destroy'])->name('api_products.delete');
    Route::post('/api-product-status', [ApiProductController::class, 'toggleStatus'])->name('api_products.toggleStatus');

    //Api Products
    Route::post('/sync-products', [ProductController::class, 'syncProducts'])->name('sync.products');
    Route::get('/import-chunk/{id}', [ProductController::class, 'importChunk'])->name('import.chunk');
    Route::get('/all-api-products', [ProductController::class, 'getApiProducts'])->name('allApiProducts');

    Route::get('/products/group/{code}', [ProductController::class, 'groupByProductCode']);

    //In House Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create-product', [ProductController::class, 'create'])->name('create.product');
    Route::post('/store-product', [ProductController::class, 'store'])->name('store.product');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('update.product');
    
    Route::get('/get-data', [ProductController::class, 'getData'])->name('get.data');

    // Category crud
    Route::get('/category', [CategoryController::class, 'getCategory'])->name('allcategory');
    Route::post('/category', [CategoryController::class, 'categoryStore'])->name('category.store');
    Route::get('/category/{id}/edit', [CategoryController::class, 'categoryEdit']);
    Route::post('/category-update', [CategoryController::class, 'categoryUpdate']);
    Route::delete('/category/{id}', [CategoryController::class, 'categoryDelete'])->name('category.delete');
    Route::post('/category-status', [CategoryController::class, 'toggleStatus']);
    Route::post('/categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.updateOrder');

    // Sub-Category crud
    Route::get('/sub-category', [SubCategoryController::class, 'getSubCategory'])->name('subcategories.index');
    Route::post('/sub-category', [SubCategoryController::class, 'subCategoryStore'])->name('subcategories.store');
    Route::get('/sub-category/{id}/edit', [SubCategoryController::class, 'subCategoryEdit'])->name('subcategories.edit');
    Route::post('/sub-category-update', [SubCategoryController::class, 'subCategoryUpdate'])->name('subcategories.update');
    Route::delete('/sub-category/{id}', [SubCategoryController::class, 'subCategoryDelete'])->name('subcategories.delete');
    Route::post('/sub-category-status', [SubCategoryController::class, 'toggleStatus'])->name('subcategories.toggleStatus');
    Route::post('/sub-category-update-serial', [SubCategoryController::class, 'updateSerial'])->name('subcategories.updateSerial');

    // Sub Sub-Category crud
    Route::get('/sub-sub-category', [SubSubCategoryController::class, 'index'])->name('subsubcategories.index');
    Route::post('/sub-sub-category', [SubSubCategoryController::class, 'store'])->name('subsubcategories.store');
    Route::get('/sub-sub-category/{id}/edit', [SubSubCategoryController::class, 'edit'])->name('subsubcategories.edit');
    Route::post('/sub-sub-category-update', [SubSubCategoryController::class, 'update'])->name('subsubcategories.update');
    Route::delete('/sub-sub-category/{id}', [SubSubCategoryController::class, 'destroy'])->name('subsubcategories.delete');
    Route::post('/sub-sub-category-status', [SubSubCategoryController::class, 'toggleStatus'])->name('subsubcategories.toggleStatus');
    Route::post('/sub-sub-category-update-serial', [SubSubCategoryController::class, 'updateSerial'])->name('subsubcategories.updateSerial');

    // Brand crud
    Route::get('/brand', [BrandController::class, 'index'])->name('brands.index');
    Route::post('/brand', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brand/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::post('/brand-update', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brand/{id}', [BrandController::class, 'destroy'])->name('brands.delete');
    Route::post('/brand-status', [BrandController::class, 'toggleStatus'])->name('brands.toggleStatus');

    // Product Model crud
    Route::get('/product-model', [ProductModelController::class, 'index'])->name('productmodel.index');
    Route::post('/product-model', [ProductModelController::class, 'store'])->name('productmodel.store');
    Route::get('/product-model/{id}/edit', [ProductModelController::class, 'edit'])->name('productmodel.edit');
    Route::post('/product-model-update', [ProductModelController::class, 'update'])->name('productmodel.update');
    Route::delete('/product-model/{id}', [ProductModelController::class, 'destroy'])->name('productmodel.delete');
    Route::post('/product-model-status', [ProductModelController::class, 'toggleStatus'])->name('productmodel.toggleStatus');

    // Group CRUD
    Route::get('/group', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/group', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/group/{id}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::post('/group-update', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/group/{id}', [GroupController::class, 'destroy'])->name('groups.delete');
    Route::post('/group-status', [GroupController::class, 'toggleStatus'])->name('groups.toggleStatus');

    // Unit CRUD
    Route::get('/unit', [UnitController::class, 'index'])->name('units.index');
    Route::post('/unit', [UnitController::class, 'store'])->name('units.store');
    Route::get('/unit/{id}/edit', [UnitController::class, 'edit'])->name('units.edit');
    Route::post('/unit-update', [UnitController::class, 'update'])->name('units.update');
    Route::delete('/unit/{id}', [UnitController::class, 'destroy'])->name('units.delete');
    Route::post('/unit-status', [UnitController::class, 'toggleStatus'])->name('units.toggleStatus');

    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::get('/tags/{id}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::post('/tags-update', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{id}', [TagController::class, 'destroy'])->name('tags.delete');
    Route::post('/tags-status', [TagController::class, 'toggleStatus'])->name('tags.toggleStatus');

    Route::get('/warranty', [WarrantyController::class, 'index'])->name('warranties.index');
    Route::post('/warranty', [WarrantyController::class, 'store'])->name('warranties.store');
    Route::get('/warranty/{id}/edit', [WarrantyController::class, 'edit'])->name('warranties.edit');
    Route::post('/warranty-update', [WarrantyController::class, 'update'])->name('warranties.update');
    Route::delete('/warranty/{id}', [WarrantyController::class, 'destroy'])->name('warranties.delete');
    Route::post('/warranty-status', [WarrantyController::class, 'toggleStatus'])->name('warranties.toggleStatus');

    Route::get('/size', [SizeController::class, 'index'])->name('sizes.index');
    Route::post('/size', [SizeController::class, 'store'])->name('sizes.store');
    Route::get('/size/{id}/edit', [SizeController::class, 'edit'])->name('sizes.edit');
    Route::post('/size-update', [SizeController::class, 'update'])->name('sizes.update');
    Route::delete('/size/{id}', [SizeController::class, 'destroy'])->name('sizes.delete');
    Route::post('/size-status', [SizeController::class, 'toggleStatus'])->name('sizes.toggleStatus');

    Route::get('/color', [ColorController::class, 'index'])->name('colors.index');
    Route::post('/color', [ColorController::class, 'store'])->name('colors.store');
    Route::get('/color/{id}/edit', [ColorController::class, 'edit'])->name('colors.edit');
    Route::post('/color-update', [ColorController::class, 'update'])->name('colors.update');
    Route::delete('/color/{id}', [ColorController::class, 'destroy'])->name('colors.delete');
    Route::post('/color-status', [ColorController::class, 'toggleStatus'])->name('colors.toggleStatus');

    Route::get('/type', [TypeController::class, 'index'])->name('types.index');
    Route::post('/type', [TypeController::class, 'store'])->name('types.store');
    Route::get('/type/{id}/edit', [TypeController::class, 'edit'])->name('types.edit');
    Route::post('/type-update', [TypeController::class, 'update'])->name('types.update');
    Route::delete('/type/{id}', [TypeController::class, 'destroy'])->name('types.delete');
    Route::post('/type-status', [TypeController::class, 'toggleStatus'])->name('types.toggleStatus');
});