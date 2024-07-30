<template>
    <div v-if="show" class="flex flex-col">
        <!--camera layout-->
        <div class="flex justify-center items-center">
            <video v-show="!isPhotoTaken" id="camera" ref="camera" :width="450" :height="337.5" :placeholder="createCameraElement()" autoplay></video>
            <canvas v-show="isPhotoTaken" id="photoTaken" :width="450" :height="337.5"  ref="canvas" class="object-contain"></canvas>
        </div>
        
        <!--button to capture image-->
        <div v-if="isCameraOpen" class="flex justify-center items-center">
            <button type="button" class="flex w-[2rem] h-[2rem] bg-slate-300 rounded-full px-1 py-1" @click="takePhoto">
                <img src="https://img.icons8.com/material-outlined/50/000000/camera--v2.png">
            </button>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'Camera',
    setup(){},
    props:{
        show:{
            type: Boolean,
            default: false
        }
    },
    data(){
        return{
            componentOptions: {},
            isCameraOpen: false,
            isPhotoTaken: false
        }
    },
    methods:{
        //access the camera of device (isCamera = true)
        createCameraElement() {
            this.isCameraOpen = true
            const constraints = (window.constraints = {
                audio: false,
                video: true
            });

            navigator.mediaDevices
                .getUserMedia(constraints)
                .then(stream => {
                    this.$refs.camera.srcObject = stream;
                })
        },
        //closes the camera
        stopCameraStream() {
            let tracks = this.$refs.camera.srcObject.getTracks();
            tracks.forEach(track => {
                track.stop();
            });
        },
        //takes photo
        async takePhoto() {
            this.isPhotoTaken = !this.isPhotoTaken
            
            //display the image in the canvas attribute.
            const context = this.$refs.canvas.getContext('2d');
            //top, bottom, width, height
            context.drawImage(this.$refs.camera, 0, 0, 450, 337.5);

            //convert from base64 to blob then into file
            const photoDataUrl = document.getElementById("photoTaken").toDataURL("image/jpeg");
            const blob = await this.dataURLtoBlob(photoDataUrl);
            const file = new File([blob], 'photo.jpg',{type: blob.type});
            this.$emit('picture-captured',file)
            
            // this.stopCameraStream()
        },
        dataURLtoBlob(dataUrl){
            const parts = dataUrl.split(';base64,');
            const contentType = parts[0].split(':')[1];
            const raw = window.atob(parts[1]);
            const rawLength = raw.length;
            const uInt8Array = new Uint8Array(rawLength);
            for (let i = 0; i < rawLength; ++i) {
                uInt8Array[i] = raw.charCodeAt(i);
            }
            return new Blob([uInt8Array], { type: contentType });
        }
    }
})
</script>