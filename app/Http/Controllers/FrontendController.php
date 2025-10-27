<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FaqQuestion;
use Illuminate\Support\Facades\Cache;
use App\Models\CompanyDetails;
use SEOMeta;
use OpenGraph;
use Twitter;
use App\Models\Master;
use App\Models\Contact;
use App\Models\ContactEmail;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Slider;
use App\Models\Section;
use App\Models\Product;
use App\Models\Category;

class FrontendController extends Controller
{
    public function index()
    {
        $company = CompanyDetails::select('meta_title', 'meta_description', 'meta_keywords', 'meta_image')->first();

        $sliders = Cache::remember('active_sliders', now()->addDay(), function () {
            return Slider::where('status', 1)
                ->orderBy('serial', 'ASC')
                ->get();
        });

        $sections = Section::where('status', 1)
            ->orderBy('sl', 'asc')
            ->get();

      $categories = Category::where('status', 1)
          ->whereHas('products', function($query) {
              $query->where('status', 1);
          })
          ->with(['products' => function($query) {
              $query->where('status', 1)
                    ->take(10);
          }])
          ->orderBy('serial', 'asc')
          ->get();

          $latestProducts = Product::with(['variants.color', 'variants.size'])
              ->where('product_source', 2)
              ->inRandomOrder()
              ->take(20)
              ->get();

          $trendingProducts = Product::with(['variants.color', 'variants.size'])
              ->where('product_source', 2)
              ->inRandomOrder()
              ->take(20)
              ->get();

        $this->seo(
            $company?->meta_title ?? '',
            $company?->meta_description ?? '',
            $company?->meta_keywords ?? '',
            $company?->meta_image ? asset('images/company/meta/' . $company->meta_image) : null
        );
      return view('frontend.index', compact('sliders', 'sections', 'categories', 'latestProducts', 'trendingProducts'));
    }

    public function customizeProduct($productId)
    {
        $product = Product::findOrFail($productId);
        return view('frontend.product.customize', compact('product'));
    }

    public function latestProducts(Request $request)
    {
        $products = Product::where('product_source', 2)->inRandomOrder()
            ->take(20)
            ->get();

        return response()->json([
            'products' => $products,
            'hasMore' => false,
        ]);
    }

    public function showProduct($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('frontend.pages.product_detail', compact('product'));
    }
    
    public function contact()
    {
      $contact = Master::firstOrCreate(['name' => 'contact']);

      if($contact){
          $this->seo(
              $contact->meta_title,
              $contact->meta_description,
              $contact->meta_keywords,
              $contact->meta_image ? asset('images/meta_image/' . $contact->meta_image) : null
          );
      }

      $company = CompanyDetails::select('address1', 'phone1', 'email1')->first();
      return view('frontend.contact', compact('contact', 'company'));
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:2|max:50',
            'last_name'  => 'required|string|min:2|max:50',
            'email' => 'required|email|max:50',
            'phone' => ['required', 'regex:/^(?:\+44|0)(?:7\d{9}|1\d{9}|2\d{9}|3\d{9})$/'],
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000'
        ]);

        $contact = new Contact();
        $contact->first_name = $request->input('first_name');
        $contact->last_name  = $request->input('last_name');
        $contact->email      = $request->input('email');
        $contact->phone      = $request->input('phone');
        $contact->subject    = $request->input('subject');
        $contact->message    = $request->input('message');
        $contact->save();

        $contactEmails = ContactEmail::where('status', 1)->pluck('email');

        // foreach ($contactEmails as $contactEmail) {
        //     Mail::mailer('gmail')->to($contactEmail)
        //       ->send(new ContactMail($contact)
        //     );
        // }

        return back()->with('success', 'Your message has been sent successfully!');
    }

    public function privacyPolicy()
    {
        $companyPrivacy = Cache::rememberForever('company_privacy', function () {
            return CompanyDetails::select('privacy_policy')->first();
        });

        return view('frontend.privacy', compact('companyPrivacy'));
    }

    public function termsAndConditions()
    {
        $companyDetails = Cache::rememberForever('company_terms', function () {
            return CompanyDetails::select('terms_and_conditions')->first();
        });
        return view('frontend.terms', compact('companyDetails'));
    }

    public function frequentlyAskedQuestions()
    {
        $faqs = FaqQuestion::orderBy('id', 'asc')->get();
        return view('frontend.faq', compact('faqs'));
    }

    private function seo($title = null, $description = null, $keywords = null, $image = null)
    {
        if ($title) {
            SEOMeta::setTitle($title);
            OpenGraph::setTitle($title);
            Twitter::setTitle($title);
        }

        if ($description) {
            SEOMeta::setDescription($description);
            OpenGraph::setDescription($description);
            Twitter::setDescription($description);
        }

        if ($keywords) {
            SEOMeta::setKeywords($keywords);
        }

        if ($image) {
            OpenGraph::addImage($image);
            Twitter::setImage($image);
        }
    }

}
