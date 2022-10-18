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
            font-size: 14px;
            letter-spacing: 0.1em;
        }

        .ticket {
            margin: auto;
            display: flex;
            background: white;
            box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
        }

        .left {
            display: flex;
        }

        .image {
            height: 250px;
            width: 250px;
            background-image: url('https://gmco-event.com/images/Logo-FIX.png');
            background-size: contain;
            opacity: 0.85;
        }

        .gmco-live {
            position: absolute;
            color: darkgray;
            height: 250px;
            padding: 0 10px;
            letter-spacing: 0.15em;
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
            height: 250px;
            width: 250px;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            padding: 5px;
            margin-right: 20px

        }

        .ticket-info {
            padding: 10px 30px;
            display: flex;
            flex-direction: column;
            text-align: center;
            justify-content: space-between;
            align-items: center;
        }

        .date {
            border-top: 1px solid gray;
            border-bottom: 1px solid gray;
            padding: 5px 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .date span {
            width: 100px;
        }

        .date span:first-child {
            text-align: left;
        }

        .date span:last-child {
            text-align: right;
        }

        .date .june-29 {
            color: #d83565;
            font-size: 20px;
        }

        .show-name {
            font-size: 24px;
            font-family: 'Metropolis', sans-serif;
            color: #b5594b;
        }

        .show-name h1 {
            font-size: 40px;
            font-weight: 700;
            font-family: 'Metropolis', sans-serif;
            letter-spacing: 0.1em;
            color: #354a62;
        }

        .time {
            padding: 10px 0;
            color: #776c6b;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-weight: 700;
        }

        .time span {
            font-weight: 400;
            color: gray;
        }

        .left .time {
            font-size: 16px;
        }


        .location {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            padding-top: 8px;
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
