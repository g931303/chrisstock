<html> 
<head>  
<style>
/* Animation properties */
.green-text {
        color: green;
        font-weight: bold;
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

/* Keyframes */
@keyframes star {
  0% {
    opacity: 0;
    transform: scale(1.5) translateY(-0.05em);
  }
  20% {
    opacity: 1;
  }
  89% {
    opacity: 1;
    transform: scale(1);
  }
  100% {
    opacity: 0;
    transform: translateZ(-8000em);
  }
}

@keyframes wars {
  0% {
    opacity: 0;
    transform: scale(1.5) translateY(0.5em);
  }
  20% {
    opacity: 1;
  }
  90% {
    opacity: 1;
    transform: scale(1);
  }
  100% {
    opacity: 0;
    transform: translateZ(-1000em);
  }
}

@keyframes spin-letters {
  0%, 10% {
    opacity: 0;
    transform: rotateY(90deg);
  }
  30% {
    opacity: 1;
  }
  70%, 86% {
    transform: rotateY(0);
    opacity: 1;
  }
  95%, 100% {
    opacity: 0;
  }
}

@keyframes move-byline {
  0% {
    transform: translateZ(5em);
  }
  100% {
    transform: translateZ(0);
  }
}

/* Make the 3D work on the container */
.starwars-demo {
  perspective: 800px;
  transform-style: preserve3d;
}

/* General styles and layout */
body {
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

.byline span {
  display: inline-block;
}

img {
  width: 100%;
}

.star, .wars, .byline {
  position: absolute;
}

.star {
  top: -0.05em;
}

.wars {
  bottom: -0.5em;
}

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

/*** Media queries for adjusting to different screen sizes ***/

@media only screen and (max-width: 600px) {
  .starwars-demo {
    font-size: 10px;
  }
}

@media only screen and (max-width: 480px) {
  .starwars-demo {
    font-size: 7px;
  }
} 
</style>

<script>  
var byline = document.getElementById('byline');   // Find the H2
bylineText = byline.innerHTML;                    // Get the content of the H2
bylineArr = bylineText.split('');                 // Split content into array
byline.innerHTML = '';                            // Empty current content

var span;         // Create variables to create elements
var letter;

for(i=0;i<bylineArr.length;i++){                  // Loop for every letter
  span = document.createElement("span");          // Create a <span> element
  letter = document.createTextNode(bylineArr[i]); // Create the letter
  if(bylineArr[i] == ' ') {                       // If the letter is a space...
    byline.appendChild(letter);         // ...Add the space without a span
  } else {
    span.appendChild(letter);           // Add the letter to the span
    byline.appendChild(span);           // Add the span to the h2
  }
}
</script>  
</head>  

<body> 
 <div class="starwars-demo">
     
    @foreach($list as $index => $item)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td class = "green-text">{{ $item }}</td>
    </tr>
    @endforeach
	
    <img src="/image/Chris_asteroid.jpg" alt="Wars" class="wars">
    <h2 class="byline" id="byline"> </h2>
</div> 
</body>
   /*** <img src=" " alt="Star" class="star"> ***/


</html>  
