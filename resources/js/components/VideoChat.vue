<template>
    <div class="">
        <div id="video-chat-window" class="fixed bg-gray-300 top-0 bottom-0 w-full h-full overflow-y-scroll grid grid-rows-3 grid-cols-2 md:grid-cols-3 gap-2 px-2 pt-2"></div>
        <div id="video-preview" class="fixed bg-gray-600 bottom-0 right-0 w-1/3 lg:w-1/5 mr-5 mb-5 sm:mr-10 sm:mb-10 overflow-hidden rounded-md shadow-xl"></div>
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
            const { connect, createLocalVideoTrack } = require('twilio-video');
            
            // Join to the Room with the given AccessToken and ConnectOptions.
            const room = await connect(this.accessToken, { audio: true, video: { width: 640, height: 640 } });
            
            // Make the Room available in the JavaScript console for debugging.
            window.room = room;

            this.addLocalParticipant(room.localParticipant)

            // Subscribe to the media published by RemoteParticipants already in the Room.
            room.participants.forEach(participant => this.addRemoteParticipant(participant));
            room.on('participantConnected', participant => this.addRemoteParticipant(participant));
        },
        addLocalParticipant: function(participant) {
            
            // Create the video container
            this.createVideoContainer(participant)

            // Attach the 
            participant.tracks.forEach(publication => {
                
                if ('audio' == publication.kind)
                    return

                this.publishTrack(publication.track, participant)
            })
        },
        addRemoteParticipant: function(participant) {

            this.createVideoContainer(participant)

            // Set up listener to monitor when a track is published and ready for use
            participant.on('trackSubscribed', track => {
                this.publishTrack(track, participant);
            });
        },
        createVideoContainer: function (participant) {
            
            // Add a container for the Participant's media.
            const div = document.createElement('div');
            div.id = participant.sid;
            div.classList.add("overflow-hidden", "rounded-md", "bg-gray-100", "z-10");

            document.getElementById('video-chat-window').appendChild(div);
        },
        publishTrack: ( track, participant ) => {
            const videoContainer = document.getElementById(participant.sid);
            videoContainer.appendChild(track.attach())
        }
    },
    mounted : function () {
        this.getAccessToken()
    }
}
</script>