<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Coming Soon | Dark Neon</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

  <style>
    /* Reset */
    * {
      box-sizing: border-box;
    }
    body, html {
      margin: 0; padding: 0; height: 100%;
      background: #0a0a0a;
      background-image: radial-gradient(circle at center, #111111 0%, #000000 80%);
      font-family: 'Poppins', sans-serif;
      color: #e0e6f8;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    /* Container */
    .coming-soon-card {
      background: rgba(10, 10, 10, 0.75);
      border-radius: 24px;
      box-shadow:
        0 0 20px rgba(41, 109, 255, 0.8),
        inset 0 0 30px rgba(20, 60, 150, 0.9);
      max-width: 480px;
      width: 90vw;
      padding: 3rem 4rem;
      text-align: center;
      backdrop-filter: blur(18px);
      border: 1.5px solid #295dff;
      user-select: none;
      animation: fadeInScale 1.3s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    }

    @keyframes fadeInScale {
      0% { opacity: 0; transform: scale(0.9); }
      100% { opacity: 1; transform: scale(1); }
    }

    /* Heading */
    h1 {
      font-family: 'Orbitron', sans-serif;
      font-weight: 700;
      font-size: 3.4rem;
      letter-spacing: 4px;
      color: #41a3ff;
      text-shadow:
        0 0 6px #41a3ff,
        0 0 12px #295dff,
        0 0 18px #295dff;
      margin-bottom: 0.4rem;
      text-transform: uppercase;
    }

    /* Subtitle */
    p.lead {
      font-weight: 400;
      font-size: 1.3rem;
      color: #9bb7ff;
      margin-bottom: 2.5rem;
      text-shadow: 0 0 4px rgba(65, 163, 255, 0.4);
    }

    /* Countdown container */
    .countdown {
      display: flex;
      justify-content: center;
      gap: 1.4rem;
      font-weight: 700;
      font-size: 3rem;
      letter-spacing: 1.5px;
      user-select: none;
    }

    /* Each countdown block */
    .countdown-item {
      background: rgba(10, 25, 50, 0.85);
      border-radius: 18px;
      min-width: 95px;
      padding: 1.6rem 1.8rem;
      box-shadow:
        0 0 12px #295dff,
        inset 0 0 15px #41a3ff;
      position: relative;
      cursor: default;
      transition: background 0.3s ease;
      text-align: center;
      color: #cce1ff;
      user-select: none;
    }

    /* Neon glow digits */
    .countdown-item > div {
      font-family: 'Orbitron', monospace;
      font-size: 2.7rem;
      color: #86baff;
      text-shadow:
        0 0 8px #41a3ff,
        0 0 20px #295dff,
        0 0 30px #295dff;
    }

    /* Label */
    .countdown-item span {
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 1.2px;
      color: #7a8dbf;
      text-shadow: 0 0 6px rgba(41, 93, 255, 0.5);
      text-transform: uppercase;
      margin-top: 0.4rem;
      display: block;
    }

    /* Bounce animation */
    .bounce {
      animation: bounceDigit 0.5s ease forwards;
    }
    @keyframes bounceDigit {
      0% {
        transform: translateY(0);
        opacity: 1;
      }
      30% {
        transform: translateY(-20px);
        opacity: 0.6;
      }
      60% {
        transform: translateY(0);
        opacity: 1;
      }
      100% {
        transform: translateY(0);
        opacity: 1;
      }
    }

    /* Hover effect */
    .countdown-item:hover {
      background: rgba(30, 50, 90, 0.9);
      box-shadow:
        0 0 20px #61a3ff,
        inset 0 0 20px #61a3ff;
      color: #a5c5ff;
    }

    /* Responsive */
    @media (max-width: 520px) {
      .coming-soon-card {
        padding: 2.6rem 3rem;
      }
      h1 {
        font-size: 2.6rem;
      }
      .countdown {
        font-size: 2.2rem;
        gap: 1rem;
      }
      .countdown-item {
        min-width: 70px;
        padding: 1.2rem 1.3rem;
      }
      .countdown-item > div {
        font-size: 1.8rem;
      }
      .countdown-item span {
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>

  <main class="coming-soon-card" role="main" aria-label="Coming Soon Landing Page">
    <h1>Coming Soon</h1>
    <p class="lead">We’re crafting something futuristic. Stay tuned!</p>

    <div class="countdown" aria-live="polite" aria-atomic="true">
      <div class="countdown-item" aria-label="Days">
        <div id="days">00</div>
        <span>Days</span>
      </div>
      <div class="countdown-item" aria-label="Hours">
        <div id="hours">00</div>
        <span>Hours</span>
      </div>
      <div class="countdown-item" aria-label="Minutes">
        <div id="minutes">00</div>
        <span>Minutes</span>
      </div>
      <div class="countdown-item" aria-label="Seconds">
        <div id="seconds">00</div>
        <span>Seconds</span>
      </div>
    </div>
  </main>

  <script>
    const countdownDate = new Date("2025-12-31T00:00:00").getTime();

    const daysEl = document.getElementById("days");
    const hoursEl = document.getElementById("hours");
    const minutesEl = document.getElementById("minutes");
    const secondsEl = document.getElementById("seconds");

    function animateDigit(el, newValue) {
      if(el.textContent === newValue) return;

      el.classList.remove('bounce');
      void el.offsetWidth;
      el.textContent = newValue;
      el.classList.add('bounce');
    }

    function updateCountdown() {
      const now = new Date().getTime();
      const distance = countdownDate - now;

      if (distance < 0) {
        animateDigit(daysEl, "00");
        animateDigit(hoursEl, "00");
        animateDigit(minutesEl, "00");
        animateDigit(secondsEl, "00");
        clearInterval(timer);
        return;
      }

      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      animateDigit(daysEl, days.toString().padStart(2, "0"));
      animateDigit(hoursEl, hours.toString().padStart(2, "0"));
      animateDigit(minutesEl, minutes.toString().padStart(2, "0"));
      animateDigit(secondsEl, seconds.toString().padStart(2, "0"));
    }

    const timer = setInterval(updateCountdown, 1000);
    updateCountdown();
  </script>

</body>
</html>
