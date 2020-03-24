@component('mail::message')
KAGARIにご登録されているパスワードのリセットを受け付けました。
<br>以下ボタンよりサイトにアクセスし、パスワードの再設定をお願いいたします。
<br>
<br>※このメールはご登録メールアドレスへ自動的にお送りしています。

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

<br>●パスワードの管理には十分ご注意ください。定期的なパスワード変更をお勧めいたします。
<br>※このメールにお心当たりがない場合、またはご不明点がございましたら、お手数ですが下記までご連絡ください。
<br>
<br>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
<br>KAGARIに関するお問い合わせは、
<br>KAGARI お客様サポート相談室へ
<br>
<br>メール：support@kagari.ai
<br>受付時間：11:00-18:00 （土日・祝除く）
<br>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
<br>デジタルマーケティングを支援するプロダクトやサービス KAGARI
<br>https://kagari.ai
<br>
<br>※本メールは送信専用アドレスからお送りしています。
<br>ご返信いただいても回答はできませんので、あらかじめご了承ください。
@endcomponent
