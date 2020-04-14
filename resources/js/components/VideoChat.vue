<template>
    <div class="p-5">
        <h1 class="text-2xl mb-4">Laravel Video Chat</h1>
        <div id="video-chat-window" class="grid grid-flow-row grid-cols-2 grid-rows-2 gap-4"></div>
    </div>
</template>

<script>
export default {
    name: 'video-chat',
    data: function () {
        return {
            accessToken: ''
        }
    },
    methods : {
        getAccessToken : function () {

            const _this = this
            const axios = require('axios')
            
            // Request a new token
            axios.get('/api/access_token')
                .then(function (response) {
                    _this.accessToken = response.data
                })
                .catch(function (error) {
                    console.log(error);
                })
                .then(function () {
                    _this.connectToRoom()
                });
        },
        connectToRoom : function () {

            const { connect, createLocalVideoTrack } = require('twilio-video');

            connect( this.accessToken, { name:'cool room' }).then(room => {
                
                console.log(`Successfully joined a Room: ${room}`);
                
                const videoChatWindow = document.getElementById('video-chat-window');

                createLocalVideoTrack().then(track => {
                    videoChatWindow.appendChild(track.attach());
                });

                room.on('participantConnected', participant => {
                    console.log(`Participant "${participant.identity}" connected`);

                    participant.tracks.forEach(publication => {
                        if (publication.isSubscribed) {
                            const track = publication.track;
                            videoChatWindow.appendChild(track.attach());
                        }
                    });

                    participant.on('trackSubscribed', track => {
                        videoChatWindow.appendChild(track.attach());
                    });
                });
            }, error => {
                console.error(`Unable to connect to Room: ${error.message}`);
            });
        }
    },
    mounted : function () {
        console.log('Video room loading...')

        this.getAccessToken()
    }
}
</script>