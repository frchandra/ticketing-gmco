<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Ticket GMCO</title>
<style>
  @import url("https://fonts.googleapis.com/css2?family=Staatliches&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Nanum+Pen+Script&display=swap");

* {
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}

body,
html {
	height: 100vh;
	display: grid;
	font-family: "Staatliches", cursive;
	background: #d78e75;
	color: black;
	font-size: 12px;
	letter-spacing: 0.1em;
}



.ticket {
	margin: auto;
	display: inline-block;
	background: white;
  width: 100vw;
  height: 24vh;
  margin-top: 30vh;
	box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
}

.left {
	display: flex;
  height: 25vh;
  
}

.image {
	height: 185px;
	width: 160px;
  background-repeat: no-repeat;
	background-image: url('https://gmco-event.com/images/Logo-FIX.png');
	background-size: cover;
	opacity: 0.85;
}

.gmco-live {
	position: absolute;
	color: darkgray;
	height: 25vh;
	padding: 7px 5px;
  
	letter-spacing: 0.16em;
  font-size: 11px;
	display: flex;
	text-align: center;
	justify-content: space-around;
	writing-mode: vertical-rl;
	transform: rotate(-180deg);
}

.gmco-live span:nth-child(2) {
	color: #776c6b;
	font-weight: 700;
}

.left .ticket-number {
	height: 200px;
	width: 175px;
	display: flex;
	justify-content: flex-end;
	align-items: flex-end;
	padding: 5px;
}

.ticket-info {
  padding-left: 20px;
	padding: 10px 0;
	display: flex;
	flex-direction: column;
	text-align: center;
	justify-content: space-between;
	align-items: center;
  width: 75vw;
  height: 25vh;
}

.date {
	border-top: 1px solid gray;
	border-bottom: 1px solid gray;
	padding: 5px 0;
  font-size: 10px;
	font-weight: 700;
  width: 50vw;
	display: flex;
	align-items: center;
	justify-content: space-around;
}

.date span {
	width: 60px;
}

.date span:first-child {
	text-align: left;
}

.date span:last-child {
	text-align: right;
}

.date .november-12 {
	color: #d83565;
	font-size: 10px;
}

.show-name {
  gap:10px;
	font-size: 14px;
	font-family: 'Metropolis', sans-serif;
	color: #b5594b;
}

.show-name h1 {
  padding-bottom: 5px;
	font-size: 26px;
	font-weight: 700;
  font-family: 'Metropolis', sans-serif;
	letter-spacing: 0.1em;
	color: #354a62;
}

.time {
  margin-top: -5px;
	margin-bottom: -10px;
	color: #776c6b;
	text-align: center;
	display: flex;
	flex-direction: column;
	
	font-weight: 700;
}

.time span {
	font-weight: 400;
	color: gray;
  font-size: 12px;
}

.left .time {
	font-size: 14px;
}


.location {
	display: flex;
	justify-content: space-around;
	align-items: center;
	width: 50vw;
	padding:4px 0;
  
	border-top: 1px solid gray;
}

.location .separator {
	font-size: 20px;
}
</style>  
</head>
<body>
  <div class="ticket">
    <div class="left">
      <div class="image">
        <p class="gmco-live">
          <span>GMCO-LIVE</span>
          <span>GMCO-LIVE</span>
          <span>GMCO-LIVE</span>
        </p>
            <div class="ticket-number">
                <p>
                    {{$data['fname']}} {{$data['lname']}}&nbsp;&nbsp;
                </p>
                <p>
                    seat: {{$data['seat']}}
                </p>
            </div>
      </div>
      <div class="ticket-info">
        <p class="date">
          <span>SATURDAY</span>
          <span class="november-12">12 NOVEMBER</span>
          <span>2022</span>
        </p>
        <div class="show-name">
          <h1>GMCO-LIVE</h1>
          <h2>SAL PRIADI</h2>
        </div>
        <div class="time">
          <p>OPEN GATE <span>@</span> 17:00 </p>
          <p>START SHOW<span>@</span> 18:30 PM</p>
        </div>
        <p class="location"><span>AUDITORIUM MM FEB UGM</span>
        </p>
      </div>
    </div>
  </div>
</body>
</html>