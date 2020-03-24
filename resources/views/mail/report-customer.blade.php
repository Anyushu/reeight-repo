@component('mail::message')
{{ $site_name }} ご担当者 様
<br>
<br>日頃よりKAGARIをご利用いただき誠にありがとうございます。
<br>
<br>「 {{ $site_url }} 」のアクセス解析レポートが完成しました。
<br>下記ボタンより、PDFデータのダウンロードをお願い致します。
<br>※レポートの生成には30秒ほどかかる場合があります。
<br>
@component('mail::button', ['url' => $action_url, 'color' => 'primary'])
レポートをダウンロード
@endcomponent
<br>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
<br>KAGARIに関するお問い合わせは、
<br>KAGARI お客様サポート相談室へ
<br>
<br>メール：support@kagari.ai
<br>受付時間：11:00-18:00 （土日・祝除く）
<br>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
<br>アクセス解析レポートの自動作成・送信ツール「KAGARI Report」
<br>https://report.kagari.ai/
<br>ページ毎のSEO分析に特化したマーケティングツール「KAGARI SEO」
<br>https://seo.kagari.ai/
<br>
<br>※本メールは送信専用アドレスからお送りしています。
<br>ご返信いただいても回答はできませんので、あらかじめご了承ください。
@endcomponent