<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>1.00</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/customer/register</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/customer/login</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/marketplace/certified-firearm-instructors</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/marketplace/start-selling</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/marketplace/gun-shows</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/marketplace/gun-ranges</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/marketplace/find-ffl</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/marketplace/clubs-and-associations</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/marketplace/contact-us</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    @foreach ($pages as $page)
        @if(isset($page->url_key))
        <url>
            <loc>{{ url('/').'/'.$page->url_key }}</loc>
            <lastmod>{{ \Carbon\Carbon::parse($page->updated_at)->tz('UTC')->toAtomString() }}</lastmod>
            <priority>0.80</priority>
        </url>
        @endif
    @endforeach
    <url>
        <loc>{{ url('/') }}/marketplace/community</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/ffl-signup</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.80</priority>
    </url>
    <url>
        <loc>{{ url('/') }}/customer/forgot-password</loc>
        <lastmod>2021-09-08T11:21:14+00:00</lastmod>
        <priority>0.64</priority>
    </url>
    @foreach ($categories as $category)
        <url>
            <loc>{{ url('/') }}/category/{{ $category->translations[0]->slug }}</loc>
            <lastmod>{{ $category->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
        @foreach($category->children as $subcategory)
            <url>
                <loc>{{ url('/') }}/category/{{$category->translations[0]->slug}}/{{ $subcategory->translations[0]->slug }}</loc>
                <lastmod>{{ $subcategory->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
        @endforeach
    @endforeach

    @foreach($sellers as $seller)
        <url>
            <loc>{{ url('/') }}/{{$seller->url}}</loc>
            <lastmod>{{ $seller->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    @foreach($products as $product)
        <url>
            <loc>{{ url('/') }}/product/{{$product->url_key}}</loc>
            @if($product->updated_at)
                <lastmod>{{ \Carbon\Carbon::parse($product->updated_at)->tz('UTC')->toAtomString() }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>