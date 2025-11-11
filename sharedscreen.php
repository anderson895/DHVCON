<button id="testShare">Test Screen Share</button>
<video id="preview" autoplay playsinline></video>

<script>
document.getElementById('testShare').addEventListener('click', async () => {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getDisplayMedia) {
        alert("Screen sharing not supported on this browser/device");
        return;
    }
    try {
        const stream = await navigator.mediaDevices.getDisplayMedia({ video: true });
        document.getElementById('preview').srcObject = stream;
        console.log("Screen sharing started", stream);
    } catch (err) {
        console.error(err);
    }
});
</script>
