<template>
  <v-container class="text-center">
        <v-row>
          <v-col cols="12">
            <v-text-field
                v-model="name"
                label="name"
            ></v-text-field>
          </v-col>

        </v-row>
        <v-row>
          <v-col cols="12">
            <v-textarea
                v-model="content"
                label="message"
                @keydown.enter.exact="send()"
            ></v-textarea>
          </v-col>

        </v-row>
        <v-row>
          <v-col cols="12">
            <v-timeline dense style="max-height: 100vh; overflow-y:auto;">
              <transition-group name="list" tag="div">
                <v-timeline-item v-for="chat in chats" :key="chat.id">
                  <v-card>
                    <v-card-title>{{ chat.name }}</v-card-title>
                    <v-card-subtitle class="text-left">{{chat.ipaddr}} {{chat.created_at}}</v-card-subtitle>
                    <v-card-text class="text-left" style="white-space: pre-line">{{ chat.content.trim() }}</v-card-text>
                  </v-card>
                </v-timeline-item>
              </transition-group>
            </v-timeline>
          </v-col>
        </v-row>
  </v-container>
</template>

<script>
  export default {
    name: 'HelloWorld',

    data: () => ({
      name: "",
      content: "",
      chats: [],
    }),
    mounted() {
      if (localStorage.getItem('username')) {
        this.$set(this, 'name', localStorage.getItem('username'));
      }
      var eventSource = new EventSource('events.php');
      eventSource.addEventListener('message', (event) => {
        var dat = JSON.parse(event.data);
        dat.id = event.lastEventId;
        this.chats.unshift(dat);
      })
    },
    methods: {
      send() {
        if (this.name.length > 0 && this.content.length > 0) {
          fetch('store.php', {
            method: 'POST',
            body: JSON.stringify({
              name: this.name,
              content: this.content
            })
          }).then(() => {
            this.$set(this, 'content', '');
          })
        }
      }
    },
    watch: {
      name(newName) {
        localStorage.setItem('username', newName);
      }
    }
  }
</script>
<style scoped>
.list-enter-active, .list-leave-active {
  transition: all 1s;
}
.list-enter, .list-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}
</style>