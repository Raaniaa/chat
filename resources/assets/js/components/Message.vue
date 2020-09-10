<template>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">Chat</div>
      <div class="card-body">
        <div class="list-messages" v-if="messages && messages.length > 0">
          <div class="media" v-for="message in messages" :key="message.id">
            <div class="media-body">
              <p>
                <span style="color:red;">
                  <strong>{{message.name}}</strong> :
                </span>
                {{message.body}}
                <img
                  v-if="message.image"
                  :src="`/uploads/${message.image}`"
                  alt
                  class="img-thumbnail"
                />
              </p>
            </div>
          </div>
        </div>
        <div class="messages-not-found" v-else>Messages not found</div>
      </div>
      <div class="card-footer">
        <div style="display:flex;" class="form-group">
          <form @submit.prevent="sendMesaage()">
            <input type="text" class="form-control" placeholder="type your chat" name="body" />
            <input type="file" name="image" id="image" />
            <button type="submit" class="btn btn-primary">Send</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      messages: [],
      message: "",
      name: $(".dropdown .dropdown-toggle").text(),
    };
  },
  sockets: {
    connect: function () {
      console.log("Socket Connected");
    },
    message: function (val) {
      this.getMessage();
    },
  },
  methods: {
    async sendMesaage() {
      console.log(event.target);
      let form = new FormData(event.target);
      form.append("name", this.name);
      try {
        await axios.post("/sendMessage", form);
        event.target.reset();
      } catch (error) {}
    },
    getMessage() {
      axios.get("/messages").then((response) => {
        console.log(response);
        this.messages = response.data;
      });
    },
  },
  mounted() {
    this.getMessage();
  },
};
</script>
