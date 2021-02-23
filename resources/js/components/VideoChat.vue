<template>
    <div class="p-5">
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
        connectToRoom : async function () {

            const _this = this
            const { createLocalTracks, connect } = require('twilio-video');
            
            const tracks = await createLocalTracks();

            // Display camera preview.
            const localVideoTrack = tracks.find(track => track.kind === 'video');
            document.body.appendChild(localVideoTrack.attach());

            connect( this.accessToken, { name:'cool room', tracks }).then(room => {
                
                room.participants.forEach(participantConnected);
                room.on('participantConnected', participantConnected);

                function participantConnected(participant) {
                    console.log('Participant "%s" connected', participant.identity);

                    const div = document.createElement('div');
                    div.id = participant.sid;
                    div.innerText = participant.identity;

                    participant.on('trackSubscribed', track => trackSubscribed(div, track));
                    participant.on('trackUnsubscribed', trackUnsubscribed);

                    participant.tracks.forEach(publication => {
                        if (publication.isSubscribed) {
                        trackSubscribed(div, publication.track);
                        }
                    });

                    document.body.appendChild(div);
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