<style>
.widget-user-header {
  background-size: cover;
  background-position: center;
}
</style>
<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Widget: user widget style 1 -->
                <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header text-white" style="background-image: url('./img/back2.jpg'); center center;">
                    <h3 class="widget-user-username"></h3>
                    <h5 class="widget-user-desc"></h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle" alt="User Avatar" :src="getProfilePhoto()">
                </div>
                <div class="card-footer">
                    <div class="row">
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                        <h5 class="description-header">3,200</h5>
                        <span class="description-text">SALES</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                        <h5 class="description-header">13,000</h5>
                        <span class="description-text">FOLLOWERS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                        <div class="description-block">
                        <h5 class="description-header">35</h5>
                        <span class="description-text">PRODUCTS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link active show" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="activity">
                        <h1>Some Content....</h1>
                        <h1>Some Content....</h1>
                        <h1>Some Content....</h1>
                  </div>

                  <!-- /.tab-pane -->

                  <div class="tab-pane active show" id="settings">
                    <form class="form-horizontal">
                      
                      <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>

                        <div class="col-sm-10">
                          <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Name" 
                          :class="{ 'is-invalid': form.errors.has('name') }">
                          <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                          <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Email"
                          :class="{ 'is-invalid': form.errors.has('email') }">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="bio" class="col-sm-2 control-label">Bio</label>

                        <div class="col-sm-10">
                          <input v-model="form.bio" type="text" class="form-control" id="bio" placeholder="Bio"
                          :class="{ 'is-invalid': form.errors.has('bio') }">
                          <has-error :form="form" field="bio"></has-error>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="type" class="col-sm-2 control-label">Type</label>

                        <div class="col-sm-10">
                          <input v-model="form.type" type="text" class="form-control" id="type" placeholder="Type" disabled>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label">Profile Pic</label>

                        <div class="col-sm-10">
                          <input type="file" @change="updateProfile" class="btn btn-default" id="photo">
                        </div>
                      </div>

                        <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                          <input type="password" v-model="form.password" class="form-control" id="password" 
                          placeholder="Leave empty if not changing"
                          :class="{ 'is-invalid': form.errors.has('password') }">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                        </div>

                        <div class="form-group" style="justify-content: center; display: flex; align-items: center">
                        <button type="submit" @click.prevent="updateInfo" class="btn btn-default" style="background-color: transparent;"><i class="fas fa-upload fa-3x text-blue"></i></button>
                        </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
        </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: ""
      })
    };
  },
  methods: {
    getProfilePhoto(){
      let photo = (this.form.photo.length > 200) ? this.form.photo : "img/profile/"+this.form.photo;
      return photo;
    },
    updateInfo(){
      if(this.form.password == ""){
            this.form.password = undefined;
          }
      this.$Progress.start();
      this.form
        .put('api/profile')
        .then(() => {
          Fire.$emit("RefreshTable");
          toast({
            type: "success",
            title: "User Updated Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        })
    },
    updateProfile(e){
      let file = e.target.files[0];

      let reader = new FileReader();

      if(file['size'] < 2111775){
        reader.onloadend = (file) => {
          this.form.photo = reader.result;
        }
        reader.readAsDataURL(file);
      }else{
        swal({
          type: 'error',
          title: 'Oops',
          text: 'You are uploading a large file.'
        });
      }
    },
    created() {
      axios.get("api/profile").then(({ data }) => this.form.fill(data));
    },
  },
  mounted() {
    console.log("Component mounted.");
    this.created();
    Fire.$on("RefreshTable", () => {
      this.created();
    });
  },
};
</script>


