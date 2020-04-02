@component('mail::message')
{{ $site_name }} ご担当者 様
<br>
<br>いつも大変お世話になっております。
<br>リエイトサポートデスクでございます。
<br>
<br>【{{ date('Y年n月', strtotime(date('Y-m-1') . '-1 month')) }}度-Webサイト解析レポート】をご提出いたします。
<br>
<br>現在のサイト({{ $site_url }})の状況をご確認ください。
<br>※レポート生成に30秒ほどかかる場合がございます。
<br>引き続きよろしくお願い致します。
<br>
@component('mail::button', ['url' => $action_url, 'color' => 'primary'])
レポートをダウンロード
@endcomponent
<br>＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/
<br>
<br>ご質問・ご相談はお気軽にお申し付けくださいませ。
<br>
<br>＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/
<br>
<br>　リエイトサポートデスク
<br>
<br>　〒630-0258 奈良県生駒市東新町4-23 第一ビル2F
<br>　TEL: 0120-800-804
<br>　Email: support@re-eight.com
<br>　リエイト公式ホームページ: http://re-eight.com(リニューアル中)
<br>
<br>＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/＿/
@endcomponent