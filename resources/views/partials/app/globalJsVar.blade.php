{{ Form::hidden('js-var-userIp', $userIpAddress) }}
{{ Form::hidden('js-var-userId', optional(Auth::user())->id) }}
{{ Form::hidden('js-var-loginActionUrl', action('Auth\LoginController@login')) }}
{{ Form::hidden('js-var-loadingImageForProduct', 'https://cdn.alaatv.com/loder.jpg?w=1&h=1') }}
{{ Form::hidden('js-var-loadingImageForVideo', 'https://cdn.alaatv.com/loder.jpg?w=9&h=5') }}
{{ Form::hidden('js-var-firebaseConfig', json_encode(config('firebaseConfig.FIREBASE_CONFIG'))) }}
{{ Form::hidden('js-var-AlaaAdBanner', 'null'
/*json_encode([
            'image'=> [
                'srcDeskTop'=> 'https://cdn.alaatv.com/upload/AlaaAdBanner-roozeMadar-DeskTop.jpg',
                'srcTablet'=> 'https://cdn.alaatv.com/upload/AlaaAdBanner-roozeMadar-Tablet.jpg',
                'srcMobile'=> 'https://cdn.alaatv.com/upload/AlaaAdBanner-roozeMadar-Mobile.jpg',
                'alt'=> 'تخفیف 50 درصدی روز مادر تا سپندارمذگان',
                'widthDeskTop'=> '1948',
                'widthTablet'=> '1024',
                'widthMobile'=> '800',
                'heightDeskTop'=> '121',
                'heightTablet'=> '115',
                'heightMobile'=> '153'
            ],
            'tooltip'=> [
                'placement'=> 'bottom',
                'title'=> 'تا 50% تخفیف روی تمامی محصولات'
            ],
            'gtmEec'=> [
                'id'=> 'AlaaAdBanner-topOfMenu',
                'name'=> 'تخفیف 50 درصدی روز مادر تا سپندارمذگان',
                'creative'=> 'همه صفحات',
                'position'=> 'بالای صفحه'
            ],
            'link'=> [
                'href'=> action("Web\ShopPageController"),
                'target'=> '_self'
            ]
        ])*/
        ) }}
