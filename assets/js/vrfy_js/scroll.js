window.onload = function() {
	
		const test_cont = document.querySelector(".cont_cover");

			test_cont.addEventListener("scroll", () => {
console.log("do");
				const scrollLeft = test_cont.scrollLeft;
				const scrollTop = test_cont.scrollTop;
				const content = document.querySelector("#contValue");
				content.style.transform = `translate(${-scrollLeft}px, ${-scrollTop}px)`;
			});
}