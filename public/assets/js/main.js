document.addEventListener("DOMContentLoaded", function () {
    var screenshotsContainers = document.querySelectorAll('.screenshots');

    screenshotsContainers.forEach(function (container) {
        var screenshots = JSON.parse(container.getAttribute('data-game-screenshots'));
        var screenshotElement = container.querySelector('.screenshot');
        var currentScreenshotIndex = 0;

        function changeScreenshot() {
            console.log("qshflkjsqgfjsqhdkjq");
            currentScreenshotIndex = (currentScreenshotIndex + 1) % screenshots.length;
            screenshotElement.src = screenshots[currentScreenshotIndex];
        }
        
        container.addEventListener("mouseover", function () {
            setTimeout(changeScreenshot, 3000);
        });
    });
});