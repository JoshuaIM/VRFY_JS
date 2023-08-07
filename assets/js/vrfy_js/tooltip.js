const icons = document.querySelectorAll('.icon');

icons.forEach(icon => {
const tooltipText = icon.getAttribute('data-tooltip');

const tooltip = document.createElement('div');
tooltip.classList.add('tooltip');
tooltip.innerText = tooltipText;

icon.parentNode.appendChild(tooltip);
});