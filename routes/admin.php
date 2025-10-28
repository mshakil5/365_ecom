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
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ApiProductController;
use App\Http\Controllers\Admin\ProductPriceController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\PartnerController;

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

    Route::get('/sync-products', [ApiProductController::class, 'syncProducts'])->name('sync.products');

    Route::get('/all-api-products', [ProductController::class, 'getApiProducts'])->name('allApiProducts');
    Route::get('product-details/{product}', [ProductController::class, 'productDetails'])->name('product.details');
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

    Route::get('/company', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('/company', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/company/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::post('/company-update', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/company/{id}', [CompanyController::class, 'destroy'])->name('companies.delete');
    Route::post('/company-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggleStatus');

    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::get('/tags/{id}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::post('/tags-update', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{id}', [TagController::class, 'destroy'])->name('tags.delete');
    Route::post('/tags-status', [TagController::class, 'toggleStatus'])->name('tags.toggleStatus');

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

    Route::get('product-price', [ProductPriceController::class, 'index'])->name('product_prices.index');
    Route::post('product-price', [ProductPriceController::class, 'store'])->name('product_prices.store');
    Route::get('product-price/{id}/edit', [ProductPriceController::class, 'edit'])->name('product_prices.edit');
    Route::post('product-price/update', [ProductPriceController::class, 'update'])->name('product_prices.update');
    Route::delete('product-price/{id}', [ProductPriceController::class, 'destroy'])->name('product_prices.destroy');
    Route::post('product-price/toggle-status', [ProductPriceController::class, 'toggleStatus'])->name('product_prices.toggleStatus');

    // Sector crud
    Route::get('/sector', [SectorController::class, 'getSector'])->name('allsector');
    Route::post('/sector', [SectorController::class, 'sectorStore'])->name('sector.store');
    Route::get('/sector/{id}/edit', [SectorController::class, 'sectorEdit']);
    Route::post('/sector-update', [SectorController::class, 'sectorUpdate']);
    Route::delete('/sector/{id}', [SectorController::class, 'sectorDelete'])->name('sector.delete');
    Route::post('/sector-status', [SectorController::class, 'toggleStatus']);
    Route::post('/sectors/update-order', [SectorController::class, 'updateOrder'])->name('sectors.updateOrder');

    Route::get('/partner', [PartnerController::class, 'index'])->name('partners.index');
    Route::post('/partner', [PartnerController::class, 'store'])->name('partners.store');
    Route::get('/partner/{id}/edit', [PartnerController::class, 'edit'])->name('partners.edit');
    Route::post('/partner-update', [PartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partner/{id}', [PartnerController::class, 'destroy'])->name('partners.delete');
    Route::post('/partner-status', [PartnerController::class, 'toggleStatus'])->name('partners.toggleStatus');

    // Stock
    Route::get('/stocks', [StockController::class, 'getStocks'])->name('allstocks');
    Route::get('/stock', [StockController::class, 'getStock'])->name('allstock');
    Route::get('/purchase', [StockController::class, 'purchase'])->name('purchase');
});