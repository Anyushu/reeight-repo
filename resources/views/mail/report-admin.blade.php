@component('mail::message')
日頃よりKAGARIをご利用いただき誠にありがとうございます。
<br>
<br>「 {{ $site_url }} 」のアクセス解析レポートを作成し、ご指定のメールアドレスへ送付いたしました。
<br>
<br>レポートのご確認は下記ボタンからダウンロードいだけます。
<br>※レポートの生成には30秒ほどかかる場合があります。
<br>
<br>【送付内容】
<br>———————————————
<br>送付サイト：{{ $site_name }}
<br>
<br>送付日時：{{ date('Y年n月j日') }}
<br>
@component('mail::button', ['url' => $action_url, 'color' => 'primary'])
レポートをダウンロード
@endcomponent
<br>
<br>※本メールは送信専用アドレスからお送りしています。
<br>ご返信いただいても回答はできませんので、あらかじめご了承ください。
@endcomponent