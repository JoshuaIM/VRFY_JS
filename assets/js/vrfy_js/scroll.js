	const test_cont = document.querySelector(".wrapper");

console.log("do");
	test_cont.addEventListener("scroll", () => {
		const scrollLeft = test_cont.scrollLeft;
		const scrollTop = test_cont.scrollTop;
		const content = document.querySelector("#contValue");
		content.style.transform = `translate(${-scrollLeft}px, ${-scrollTop}px)`;
	});