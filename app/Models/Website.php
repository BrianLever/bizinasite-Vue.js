<?php

namespace App\Models;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;

class Website extends Model
{
    protected $table = 'websites';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
      'module_url'=>'json'
    ];

    public function getDataAttribute($value){
        return json_decode($value);
    }

    public static function boot() {

        parent::boot();

        static::saving(function($obj) {
            unset($obj['pages']);
        });
    }

    public function configure()
    {
//        makedir(storage_path('framework/sessions/'.$this->id), 0775);
//
//        config([
//            'session.files' => storage_path('framework/sessions/'.$this->id), //session path
//            'cache.stores.file.path'=> storage_path('framework/cache/data/'.$this->id), //cache file prefix
//            'cache.prefix'=> "bizinasite_" . $this->id, //cache prefix
//            'app.url'=>"https://" . $this->domain
//        ]);

        config([
            'cache.prefix'=> "bizinasite_" . $this->id, //cache prefix
            'app.url'=>"https://" . $this->domain
        ]);
        app('cache')->forgetDriver(
            config('cache.default')
        );

        if(is_array($this->module_url))
        {
            foreach($this->module_url as $key=>$url)
            {
                config([
                    "custom.route.{$key}"=>$url
                ]);
            }
        }

        //FileSystem Disk config custom
//        $elfidner_disks = [];

//        $disks = config("filesystems.disks");
//        foreach($disks as $key=>$disk)
//        {
//            config([
//                "filesystems.disks.{$key}.root"=>$disk['root'] . "/" . $this->id
//            ]);
//        }
        config([
            "filesystems.disks.storage.root"=> config("filesystems.disks.storage.root")."/" . $this->id,
            "filesystems.disks.s3-pub-bizinasite.root"=>$this->id,
        ]);
        //Elfinder Disks

//        config([
//           'elfinder.disks'=> $elfidner_disks
//        ]);

        return $this;
    }

    public function use()
    {
        app()->forgetInstance('tenant');

        app()->instance('tenant', $this);

        return $this;
    }

    public function pages()
    {
        if($this->version === 1){
            return $this->hasMany(Page::class, 'web_id');
        }else{
            return $this->hasMany(Page::class, 'web_id')->with('sections');
        }
    }
    public function header()
    {
        return $this->hasOne(Header::class, 'web_id')->withDefault();
    }
    public function footer()
    {
        return $this->hasOne(Footer::class, 'web_id')->withDefault();
    }
    public function getModules()
    {
        return $this->hasMany(WebsiteModule::class, 'web_id');
    }
    public function hasAnyActiveModule($module)
    {
        foreach($module as $item)
        {
            if(in_array($item, tenant("c_modules"))) return true;
        }
        return false;
    }
    public function hasAnyPublishModule($module)
    {
        foreach($module as $item)
        {
            if(in_array($item, tenant("p_modules"))) return true;
        }
        return false;
    }
    public function getHeader($preview=0)
    {
        $pages = $this->pages()
            ->with('website', 'activeSubPages')
            ->status(1)
            ->where('parent_id', 0)
            ->where('header', 1)
            ->orderBy('order')
            ->select('parent_id', 'name', 'url', 'type',  'web_id', 'id')
            ->get();
        $header = $this->header;
        $blade = Blade::compileString($header->content);

        $__data['pages'] = $pages;
        $__data['logo'] = optional(option("basic", null))['logo'];
        $__data['preview'] = $preview;
        $__data['__env'] = app(\Illuminate\View\Factory::class);

        $component =  $this->bladeRender($blade, $__data);
        return $component;
    }
//    public function getFooter($preview)
//    {
//        $pages = $this->pages()->with('website')
//            ->status(1)
//            ->where('footer', 1)
//            ->orderBy('footer_order')
//            ->select('parent_id', 'name', 'url', 'web_id', 'id')
//            ->get();
//        $footer = $this->footer;
//
//        $blade = Blade::compileString($footer->content);
//        $__data['pages'] = $pages;
//        $__data['preview'] = $preview;
//        $__data['__env'] = app(\Illuminate\View\Factory::class);
//        $component =  $this->bladeRender($blade, $__data);
//        return $component;
//
//    }
    function bladeRender($__php, $__data)
    {
        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            eval('?' . '>' . $__php);
        } catch (\Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new FatalThrowableError($e);
        }
        return ob_get_clean();
    }
    public function isStorageLimited()
    {
        $limit = $this->storage_limit;
        if($limit==-1) return false;
        $current = $this->getStorageSize();
        $limit = $limit*1024*1024*1024;
        if($current>=$limit)
        {
            return true;
        }
        return false;
    }
    public function getStorageSize()
    {
       return $this->getFolderSize($this->id);
    }
    public function getStorageCount()
    {
       return $this->getFolderSize($this->id, 'count');
    }
    public function getFolderSize($folder, $return='size')
    {
        $current_size = 0;
        $count = 0;

        $s3 = S3Client::factory([
            'version' => 'latest',
            'region'  => config("custom.aws.region"),
            'endpoint' => config("custom.aws.endpoint"),
            'credentials' => array(
                'key' => config( "custom.aws.key"),
                'secret'  => config("custom.aws.secret"),
            )
        ]);
        $objects = $s3->getIterator('ListObjects', array(
            "Bucket" => config("custom.aws.bucket"),
            "Prefix" => $folder
        ));
        foreach ($objects as $object) {
            $current_size = $current_size+$object['Size'];
            $count++;
        }
        if($return=='size')
        {
            return $current_size;
        }else {
            return $count;
        }
    }
    public function generateSiteMap()
    {
        \URL::forceRootUrl("https://" . tenant("domain"));
        \URL::forceScheme('https');

        $sitemap  = Sitemap::create();
        $pages = Page::where('header', 1)
            ->where('status', 1)
            ->orderBy('order')
            ->get();

        $date = Carbon::now();
        $frequency = Url::CHANGE_FREQUENCY_WEEKLY;

        //normal pages including module;

        foreach($pages as $page)
        {
            $sitemap->add(
                Url::create(
                    $this->removeRootUrl(
                        $page->getUrl()
                    )
                )
                    ->setPriority(0.8)
                    ->setLastModificationDate($date)
                    ->setChangeFrequency($frequency)
            );
        }
        //blog pages
        if(tenant()->hasAnyPublishModule(['simple_blog', 'advanced_blog', 'blogAds']))
        {
            //blog category pages

            $blogCategories = BlogCategory::where("status", 1)->get();
            foreach($blogCategories as $blogCategory)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('blog.category', $blogCategory->slug)
                        )
                    )
                        ->setPriority(0.6)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }

            //blog tags

            $blogTags = BlogTag::where("status", 1)->get();
            foreach($blogTags as $blogTag)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('blog.tag', $blogTag->slug)
                        )
                    )
                        ->setPriority(0.6)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }

            //blog Posts

            $blogPosts = BlogPost::frontVisible()->get();
            foreach($blogPosts as $blogPost)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('blog.detail', $blogPost->slug)
                        )
                    )
                        ->setPriority(0.4)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }


            if(tenant()->hasAnyPublishModule(['advanced_blog']))
            {
                //blog Authors

                $blogAuthors = User::whereHas("posts")
                    ->where("status", "active")
                    ->get();

                foreach($blogAuthors as $blogAuthor)
                {
                    $sitemap->add(
                        Url::create(
                            $this->removeRootUrl(
                                route('blog.author', $blogAuthor->id)
                            )
                        )
                            ->setPriority(0.4)
                            ->setLastModificationDate($date)
                            ->setChangeFrequency($frequency)
                    );
                }

                //blog packages

                $blogPackages = BlogPackage::where("status", 1)->get();

                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('blog.package')
                        )
                    )
                        ->setPriority(0.6)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );

                foreach($blogPackages as $blogPackage)
                {
                    $sitemap->add(
                        Url::create(
                            $this->removeRootUrl(
                                route('blog.package.detail', $blogPackage->slug)
                            )
                        )
                            ->setPriority(0.4)
                            ->setLastModificationDate($date)
                            ->setChangeFrequency($frequency)
                    );
                }



            }


            if(tenant()->hasAnyPublishModule(['blogAds']))
            {
                $blogAdsSpots = BlogAdsSpot::where("status", 1)->get();
                foreach($blogAdsSpots as $blogAdsSpot)
                {
                    $sitemap->add(
                        Url::create(
                            $this->removeRootUrl(
                                route('blogAds.spot', $blogAdsSpot->slug)
                            )
                        )
                            ->setPriority(0.4)
                            ->setLastModificationDate($date)
                            ->setChangeFrequency($frequency)
                    );
                }
            }

        }

        if(tenant()->hasAnyPublishModule(['directory', 'directoryAds']))
        {
            $directoryCategories = DirectoryCategory::where("status", 1)->get();
            foreach($directoryCategories as $directoryCategory)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('directory.category', $directoryCategory->slug)
                        )
                    )
                        ->setPriority(0.6)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }


            $directoryTags = DirectoryTag::where("status", 1)->get();
            foreach($directoryTags as $directoryTag)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('directory.tag', $directoryTag->slug)
                        )
                    )
                        ->setPriority(0.6)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }


            $directoryListings = DirectoryListing::frontVisible()->get();
            foreach($directoryListings as $directoryListing)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('directory.detail', $directoryListing->slug)
                        )
                    )
                        ->setPriority(0.4)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }

            //directory packages

            $directoryPackages = DirectoryPackage::where("status", 1)->get();

            $sitemap->add(
                Url::create(
                    $this->removeRootUrl(
                        route('directory.package')
                    )
                )
                    ->setPriority(0.6)
                    ->setLastModificationDate($date)
                    ->setChangeFrequency($frequency)
            );

            foreach($directoryPackages as $directoryPackage)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('directory.package.detail', $directoryPackage->slug)
                        )
                    )
                        ->setPriority(0.4)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }

            //directory ads
            if(tenant()->hasAnyPublishModule(['directoryAds']))
            {
                $directoryAdsSpots = DirectoryAdsSpot::where("status", 1)->get();
                foreach($directoryAdsSpots as $directoryAdsSpot)
                {
                    $sitemap->add(
                        Url::create(
                            $this->removeRootUrl(
                                route('directoryAds.spot', $directoryAdsSpot->slug)
                            )
                        )
                            ->setPriority(0.4)
                            ->setLastModificationDate($date)
                            ->setChangeFrequency($frequency)
                    );
                }
            }

        }

        if(tenant()->hasAnyPublishModule(['siteAds']))
        {
            $siteAdsSpots = SiteAdsSpot::where("status", 1)->get();
            foreach($siteAdsSpots as $siteAdsSpot)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('siteAds.spot', $siteAdsSpot->slug)
                        )
                    )
                        ->setPriority(0.4)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }
        }

        if(tenant()->hasAnyPublishModule(['portfolio']))
        {
            $portfolios = Portfolio::where("status", 1)->get();
            foreach($portfolios as $portfolio)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('portfolio.detail', $portfolio->slug)
                        )
                    )
                        ->setPriority(0.4)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }
        }

        if(tenant()->hasAnyPublishModule(['ecommerce']))
        {
            $ecommerceProducts = EcommerceProduct::frontVisible()->get();
            foreach($ecommerceProducts as $ecommerceProduct)
            {
                $sitemap->add(
                    Url::create(
                        $this->removeRootUrl(
                            route('ecommerce.detail', $ecommerceProduct->slug)
                        )
                    )
                        ->setPriority(0.4)
                        ->setLastModificationDate($date)
                        ->setChangeFrequency($frequency)
                );
            }
        }

        $sitemap->writeToDisk('s3-pub-bizinasite', 'sitemap.xml');

        $google_services = option("google_services", []);

        $google_services["sitemap_updated"] = now()->toDateTimeString();

        option(["google_services"=>$google_services]);

    }
    public function removeRootUrl($url)
    {
        $root = url('/');
        $result = str_replace($root, '', $url);
        if($result=='') return '/';
        else return $result;
    }
}
