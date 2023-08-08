    const icons = document.querySelectorAll('.info-icon');

    icons.forEach(icon => {
        // const tooltipText = icon.getAttribute('data-tooltip');
        const tooltipText = "이건 테스트 입니다.";

        const tooltip = document.createElement('div');
        tooltip.classList.add('info-tooltip');
        tooltip.innerText = tooltipText;

        icon.parentNode.appendChild(tooltip);
    });