<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
   <defs>
    <linearGradient id="lockGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#4f46e5" />
      <stop offset="100%" stop-color="#3b82f6" />
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#4f46e5" flood-opacity="0.5"/>
    </filter>
  </defs>
  
  <rect x="5" y="5" width="90" height="90" rx="25" fill="url(#lockGrad)" filter="url(#shadow)"/>
  
  <path d="M 33 45 V 35 C 33 25.6 50 20 50 35 C 50 20 67 25.6 67 35 V 45" fill="none" stroke="white" stroke-width="8" stroke-linecap="round"/>
  <!-- Better math for simple shackle -->
  <path d="M 33 45 V 35 A 17 17 0 0 1 67 35 V 45" fill="none" stroke="white" stroke-width="7" stroke-linecap="round"/>
  
  <rect x="23" y="45" width="54" height="36" rx="8" fill="white"/>
  
  <!-- Keyhole -->
  <rect x="47" y="54" width="6" height="10" rx="3" fill="#4f46e5"/>
  <circle cx="50" cy="56" r="4" fill="#4f46e5"/>
</svg>
