<template>
    <div class="p-5">
        <div id="video-chat-window" class=""></div>
        <div id="video-preview" class="fixed bg-gray-600 bottom-0 right-0 w-1/2 md:w-1/3 lg:w-1/4 mr-5 mb-5 sm:mr-10 sm:mb-10 overflow-hidden rounded-md shadow-xl"></div>
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
        connectToRoom : async function () {

            const _this = this
            const { createLocalTracks, connect } = require('twilio-video');
            
            const tracks = await createLocalTracks();

            // Display camera preview.
            const localVideoTrack = tracks.find(track => track.kind === 'video');
            document.getElementById('video-preview').appendChild(localVideoTrack.attach());

            connect( this.accessToken, { name:'default-room', tracks }).then(room => {
                
                room.participants.forEach(participantConnected);
                room.on('participantConnected', participantConnected);

                function participantConnected(participant) {
                    console.log('Participant "%s" connected', participant.identity);

                    const div = document.createElement('div');
                    div.id = participant.sid;
                    div.classList.add("overflow-hidden", "rounded-md", "h-full");

                    participant.on('trackSubscribed', track => trackSubscribed(div, track));
                    participant.on('trackUnsubscribed', trackUnsubscribed);

                    participant.tracks.forEach(publication => {
                        if (publication.isSubscribed) {
                            trackSubscribed(div, publication.track);
                        }
                    });

                    document.getElementById('video-chat-window').appendChild(div);
                }

                function participantDisconnected(participant) {
                    console.log('Participant "%s" disconnected', participant.identity);
                    document.getElementById(participant.sid).remove();
                }

                function trackSubscribed(div, track) {
                    div.appendChild(track.attach());
                }

                function trackUnsubscribed(track) {
                    track.detach().forEach(element => element.remove());
                }
            })
        },
    },
    mounted : function () {
        console.log('Video room loading...')

        this.getAccessToken()
    }
}
</script>