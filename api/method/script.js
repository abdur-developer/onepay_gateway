function copyToClipboard(id) {
    const element = document.getElementById(id);
    const text = element.innerText;
    
    navigator.clipboard.writeText(text).then(() => {
      // Change button temporarily
      const btn = element.nextElementSibling;
      const originalHtml = btn.innerHTML;
      
      btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
      btn.style.backgroundColor = 'var(--success-color)';
      
      setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.style.backgroundColor = 'var(--primary-color)';
      }, 2000);
    }).catch(err => {
      console.error('Failed to copy: ', err);
    });
  }
  
  // Add animation on load
  document.addEventListener('DOMContentLoaded', () => {
    const card = document.querySelector('.payment-card');
    setTimeout(() => {
      card.classList.remove('animation-pulse');
    }, 1000);
  });

