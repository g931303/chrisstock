<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&display=swap" rel="stylesheet">
<style>
.green-text {
    color: green;
    font-weight: bold;
    font-size:2em;
}
.star {
  animation: star 10s ease-out infinite;
}
.wars {
  animation: wars 10s ease-out infinite;
}
.byline span {
  animation: spin-letters 10s linear infinite;
}
.byline {
  animation: move-byline 10s linear infinite;
}
@keyframes star {
  0% { opacity: 0; transform: scale(1.5) translateY(-0.05em); }
  20% { opacity: 1; }
  89% { opacity: 1; transform: scale(1); }
  100% { opacity: 0; transform: translateZ(-8000em); }
}
@keyframes wars {
  0% { opacity: 0; transform: scale(1.5) translateY(0.5em); }
  20% { opacity: 1; }
  90% { opacity: 1; transform: scale(1); }
  100% { opacity: 0; transform: translateZ(-1000em); }
}
@keyframes spin-letters {
  0%, 10% { opacity: 0; transform: rotateY(90deg); }
  30% { opacity: 1; }
  70%, 86% { transform: rotateY(0); opacity: 1; }
  95%, 100% { opacity: 0; }
}
@keyframes move-byline {
  0% { transform: translateZ(5em); }
  100% { transform: translateZ(0); }
}
.starwars-demo {
  perspective: 800px;
  transform-style: preserve3d;
}
body {
   
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #000 url(//cssanimation.rocks/demo/starwars/images/bg.jpg);

}
.starwars-demo {
  height: 17em;
  left: 50%;
  position: absolute;
  top: 53%;
  transform: translate(-50%, -50%);
  width: 34em;
}
.byline span { display: inline-block; }
img { width: 100%; }
.star, .wars, .byline { position: absolute; }
.star { top: -0.05em; }
.wars { bottom: -0.5em; }
.byline {
  color: #fff;
  font-family: "ITC Serif Gothic", Lato;
  font-size: 2.25em;
  left: -2em;
  letter-spacing: 0.4em;
  right: -2em;
  text-align: center;
  text-transform: uppercase;
  top: 29%;
}
table { color: white; margin: 10px auto; }
/* Phone layout: stack title, table and image in normal flow instead of fixed desktop offsets */
@media only screen and (max-width: 600px) {
  body { display: block; overflow-x: hidden; }
  .back-btn { top: 12px !important; left: 12px !important; padding: 6px 12px !important; font-size: 0.9em !important; }
  .page-title { position: static !important; margin-top: 60px !important; padding: 10px 12px !important; font-size: 1.6em !important; line-height: 1.3; }
  .starwars-demo { position: static; transform: none !important; width: auto; height: auto; margin: 0 auto 20px; padding: 0 12px; font-size: 10px; }
  .green-text { font-size: 1.1rem; }
  th.green-text, td.green-text { padding: 4px 8px !important; }
  .byline { display: none; }
  .wars { max-width: 320px; margin-top: 16px !important; }
}
</style>
</head>  
<body>

<a class="back-btn" href="{{ url('/') }}" style="position: absolute; top: 20px; left: 20px; z-index: 20; padding: 8px 16px; background: rgba(0,0,0,0.6); border: 2px solid green; border-radius: 4px; color: green; font-weight: bold; text-decoration: none; font-size: 1em;">&larr; Back</a>

<p class="page-title" style="position: absolute; top: 120px; left: 0; right: 0; z-index: 10; margin: 0; padding: 20px 0; color: green; font-weight: bold; text-align: center; font-size: 2.5em; font-family: 'Pinyon Script', cursive;">The prediction price of Stock No : <span style="font-family: sans-serif;">{{ $stock ?? 'N/A' }}</span> in future 10 days:</p>

<div class="starwars-demo" style="display: flex; flex-direction: column; justify-content: space-between; align-items: center; text-align: center; transform: translateY(-240px) translateX(-200px);">
    @if(isset($rows) && count($rows) > 0 && isset($rows[0]['price_base']) && $rows[0]['price_base'] !== null)
    <table style="position: relative; z-index: 10; margin: 0 auto; border-collapse: collapse;">
        <tr>
            <th class="green-text" style="padding: 2px 10px;">Day</th>
            <th class="green-text" style="padding: 2px 10px;">Base</th>
            <th class="green-text" style="padding: 2px 10px;">Sentiment Adj</th>
        </tr>
        @foreach($rows as $row)
            <tr>
                <td class="green-text" style="text-align: center; padding: 2px 10px;">{{ $row['day'] }}</td>
                <td class="green-text" style="text-align: center; padding: 2px 10px;">{{ number_format((float) $row['price_base'], 2) }}</td>
                <td class="green-text" style="text-align: center; padding: 2px 10px;">{{ number_format((float) $row['price'], 2) }}</td>
            </tr>
        @endforeach
    </table>
    @elseif(isset($list) && count($list) > 0)
    <table style="position: relative; z-index: 10; margin: 0 auto;">
        @foreach($list as $item)
            <tr>
                <td class="green-text" style="text-align: center;">{{ is_numeric($item) ? number_format((float) $item, 2) : $item }}</td>
            </tr>
        @endforeach
    </table>
    @endif

    <h2 class="byline" id="byline"></h2>

    <img src="/image/Chris_asteroid.jpg" alt="Wars" class="wars" style="position: relative; margin-top: auto;">

</div>
<script>  
// Script moved to bottom so DOM is ready
var byline = document.getElementById('byline');
bylineText = byline.innerHTML;
bylineArr = bylineText.split('');
byline.innerHTML = '';
var span;
var letter;
for(i = 0; i < bylineArr.length; i++) {
  span = document.createElement("span");
  letter = document.createTextNode(bylineArr[i]);
  if(bylineArr[i] == ' ') {
    byline.appendChild(letter);
  } else {
    span.appendChild(letter);
    byline.appendChild(span);
  }
}
</script>  
</body>
</html>
