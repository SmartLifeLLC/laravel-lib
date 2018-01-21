<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2017/12/20
 * Time: 11:42
 */

namespace App\Lib;


class RandomFacebookUser
{


    private $facebookData = [];

    public function __construct($facebookId)
    {
        $firstName = $this->generateFirstName();
        $lastName = $this->generateLastName();
        $this->facebookData =
        [
            "id"=> $facebookId,
            "email"=>$this->generateEmail(),
            "gender"=>(rand(0,1)==0)?"male":"female",
            "link"=>"https://www.facebook.com",
            "local"=>"ja_JP",
            "name" =>$firstName.' '.$lastName,
            "timezone" => 9,
            "updated_time"=>"2017-12-03T14:40:58+0000",
            "verified"=>true,
            "last_name"=>$lastName,
            "first_name"=>$firstName,
            "birthday"=>$this->generateBirthday(),
            "about"=>"自己紹介 : " . $this->generateDescription(),
            "picture"=>
                [
                    "data"=>
                    [
                        "height"=>50,
                        "is_silhouette"=>false,
                        "url"=>"https://scontent.xx.fbcdn.net/v/t1.0-1/c0.0.50.50/p50x50/1619293_10203228148613593_1517625984_n.jpg?oh=5b0d97dd8cf1a958c4f1642dbd2f29ec&oe=5ACA364E",
                        "width"=>50
                    ]
                ],
            "cover"=>
                [
                    "id" => "10214860128965832",
                    "offset_x" => 0 ,
                    "offset_y" => 29,
                    "source" => "https://scontent.xx.fbcdn.net/v/t31.0-8/s720x720/24273699_10214860128965832_5611334576648402735_o.jpg?oh=82d2cfba19882e55046031f5f7c9df3b&oe=5AD25F1C"

                ]

        ];

    }

    public function getJsonData(){
        return json_encode($this->facebookData);
    }

    public function getData(){
    	return $this->facebookData;
    }

    public function generateFacebookId(){
        $facebookId = Util::getUniqueNumber(); // Facebook ID
        return $facebookId;
    }

    public function generateEmail(){
        $email = Util::getRandomString((rand(15,20))); // email
        return $email;
    }

    public function generateFirstName(){
        $firstName = Util::getRandomString("10");
        return $firstName;
    }

    public function generateLastName(){
        $lastName = Util::getRandomString("20");
        return $lastName;
    }

    public function generateDescription(){
        return "desc_".Util::getRandomString("50");
    }

    public function generateBirthday(){
        return rand(10,12)."/".rand(10,29)."/".rand(1970,2000);
    }

    public function generateProfileImageId(){
        $profileImageIds =   [1 ,   3 ,   5 ,   7 ,   9 ,  11 ,  13 ,  55 ,  62 ,  64 ,  66 ,  68 ,  71 ,  73 ,  75 ,  81 ,  84 ,  90 ,  96 ,  99 , 101 , 103 , 106 , 108 , 110 , 112 , 114 , 116 , 118 , 120 , 122 , 124 , 126 , 128 , 130 , 132 , 134 , 136 , 138 , 140 , 142 , 144 , 146 , 150 , 152 , 154 , 156 , 158 , 160 , 162 , 164 , 166 , 168 , 170 , 172 , 174 , 176 , 178 , 180 , 182 , 184 , 186 , 188 , 190 , 192 , 194 , 196 , 198 , 200 , 202 , 204 , 206 , 208 , 210 , 212 , 214 , 216 , 218 , 220 , 222 , 224 , 226 , 228 , 230 , 239 , 241 , 247 , 248 , 249 , 250 , 251 , 254 , 256 , 262 , 264 , 266 , 268 , 270 , 272 , 279 , 284 , 286 , 288 , 291 , 293 , 295 , 297 , 299 , 302 , 306 , 310 , 312 , 314 , 323 , 325 , 327 , 329 , 331 , 333 , 335 , 338 , 341 , 343 , 346 , 348 , 350 , 353 , 358 , 360 , 362 , 364 , 366 , 369 , 371 , 373 , 375 , 377 , 379 , 385 , 391 , 393 , 395 , 397 , 399 , 401 , 403 , 412 , 414 , 417 , 419 , 421 , 423 , 425 , 427 , 429 , 431 , 433 , 435 , 437 , 439 , 441 , 443 , 445 , 447 , 449 , 458 , 460 , 462 , 465 , 471 , 473 , 475 , 477 , 479 , 486 , 488 , 490 , 492 , 494 , 498 , 503 , 505 , 508 , 510 , 512 , 514 , 516 , 518 , 520 , 522 , 532 , 541 , 543 , 546 , 563 , 565 , 571 , 575 , 577 , 579 , 581 , 583 , 585 , 593 , 595 , 597 , 606 , 608 , 610 , 612 , 614 , 616 , 619 , 621 , 623 , 626 , 630 , 632 , 634 , 641 , 644 , 646 , 648 , 650 , 652 , 665 , 667 , 669 , 675 , 681 , 684 , 694 , 696 , 701 , 703 , 709 , 711 , 713 , 720 , 722 , 724 , 726 , 728 , 730 , 733 , 736 , 746 , 749 , 751 , 753 , 755 , 760 , 762 , 764 , 766 , 768 , 771 , 773 , 775 , 777 , 779 , 783 , 785 , 788 , 790 , 793 , 795 , 799 , 801 , 806 , 810 , 816 , 818 , 820 , 822 , 828 , 830 , 832 , 834 , 836 , 838 , 840];
        return $profileImageIds[rand(0,count($profileImageIds)-1)];
    }

    public function generateCoverImageId(){
        $coverImageIds = [2,  4,  6,  8, 10, 12, 14, 56, 63, 65, 67, 69, 72, 74, 76, 82, 85, 91, 97,100,102,104,107,109,111,113,115,117,119,121,123,125,127,129,131,133,135,137,139,141,143,145,147,151,153,155,157,159,161,163,165,167,169,171,173,175,177,179,181,183,185,187,189,191,193,195,197,199,201,203,205,207,209,211,213,215,217,219,221,223,225,227,229,231,240,242,252,253,255,257,263,265,267,269,271,273,280,285,287,289,292,294,296,298,300,303,307,311,313,315,324,326,328,330,332,334,336,339,342,344,347,349,351,354,359,361,363,365,367,370,372,374,376,378,380,386,392,394,396,398,400,402,404,413,415,418,420,422,424,426,428,430,432,434,436,438,440,442,444,446,448,450,459,461,463,466,472,474,476,478,480,487,489,491,493,495,499,504,506,509,511,513,515,517,519,521,523,533,542,544,547,564,566,572,576,578,580,582,584,586,594,596,598,607,609,611,613,615,617,620,622,624,627,631,633,635,642,645,647,649,651,653,666,668,670,676,682,685,695,697,702,704,710,712,714,721,723,725,727,729,731,734,737,747,750,752,754,756,761,763,765,767,769,772,774,776,778,780,784,786,789,791,794,796,800,802,807,811,817,819,821,823,829,831,833,835,837,839,841];
        return $coverImageIds[rand(0,count($coverImageIds)-1)];;
    }

    public function generateGenderPublishedFlag(){
        return (rand(0,1) == 0);
    }

    public function generateBirthdayPublishedFlag(){
        return (rand(0,1) == 0);
    }

    public function generateAddress(){
        return Util::getRandomString("100");
    }
}