<template>
    <div class="container">
      <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">

                    <!-- SEARCH BOX -->
                    <!-- <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                        <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div> -->
                    <!-- END SEARCH BOX -->

                    <button class="btn btn-default" style="background-color: transparent;" @click="newModel">
                        <i class="fas fa-user-plus fa-2x text-green"></i>
                    </button>       
                </div>
                
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Registered At</th>
                    <th>Modify</th>
                  </tr>
                  <tr v-for="user in users" :key="user.id">
                    <td>{{user.id}}</td>
                    <td>{{user.name | upText}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.type | upText}}</td>
                    <td>{{user.created_at | myDate}}</td>
                    <td>
                        <a href="#" @click="editModel(user)">
                            <i class="fas fa-user-edit fa-lg text-orange"></i>
                        </a>
                        &nbsp;
                        <a href="#" @click="deleteUser(user.id)">
                            <i class="fas fa-user-times fa-lg text-red"></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="addNewUserTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="addNewUserTitle">Add New User</h5>
                <h5 class="modal-title" v-show="editmode" id="updateUserTitle">Update User's Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form @submit.prevent="editmode ? updateUser() : createUser() ">
            <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name" placeholder="Enter User Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>

                <div class="form-group">
                  <input v-model="form.email" type="email" name="email" placeholder="Enter Email Address"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                  <has-error :form="form" field="email"></has-error>
                </div>
                
                <div class="form-group">
                  <textarea cols="30" rows="5" id="bio" v-model="form.bio" type="text" name="bio" placeholder="Enter Bio (Optional)"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                  <has-error :form="form" field="bio"></has-error>
                </div>

                <div class="form-group">
                  <select v-model="form.type" id="type" type="text" name="type"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                    <option value="" disabled selected>Select User Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">Standard User</option>
                    <option value="author">Author</option>
                    
                    </select>
                  <has-error :form="form" field="type"></has-error>
                </div>

                <div class="form-group">
                  <input v-model="form.password" type="password" name="password" placeholder="Enter Password"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                  <has-error :form="form" field="password"></has-error>
                </div>

            </div>
        
            <div class="modal-footer" style="justify-content: center; display: flex; align-items: center">
                <button type="button" data-dismiss="modal" class="btn btn-default" style="background-color: transparent;"><i class="fas fa-times-circle fa-3x text-fade-red"></i></button>
                <button v-show="!editmode" class="btn btn-default" type="submit" style="background-color: transparent;"><i class="fas fa-check-circle fa-3x text-green"></i></button>
                <button v-show="editmode" class="btn btn-default" type="submit" style="background-color: transparent;"><i class="fas fa-check-circle fa-3x text-orange"></i></button>
            </div>
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
      editmode: true,
      users: {},
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

  /*==============ALL METHODS==============*/

  methods: {
    /*==============FOR OPENING NEW MODEL==============*/
    newModel() {
      this.editmode = false;
      this.form.reset();
      this.form.clear();
      $("#addNewUser").modal("show");
    },
    /*==============END FOR OPENING NEW MODEL==============*/

    /*==============FOR EDITING USER==============*/
    editModel(user) {
      this.editmode = true;
      this.form.reset();
      this.form.clear();
      $("#addNewUser").modal("show");
      this.form.fill(user);
    },
    /*==============END FOR EDITING USER==============*/

    /*==============FOR UPDATING USER==============*/
    updateUser() {
      this.$Progress.start();
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewUser").modal("hide");
          toast({
            type: "success",
            title: "User Updated Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
        });
      // console.log('Editing User......');
    },
    /*==============END FOR UPDATING USER==============*/

    /*==============FOR DELETING USER==============*/
    deleteUser(id) {
      swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      })
        .then(result => {
          if (result.value) {
            this.form.delete("api/user/" + id).then(() => {
              swal("Deleted!", "Your user has been deleted.", "success");
              Fire.$emit("RefreshTable");
            });
          }
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
        });
    },
    /*==============END FOR DELETING USER==============*/

    /*==============LOADING USER==============*/
    loadUsers() {
      axios.get("api/user").then(({ data }) => (this.users = data.data));
    },
    createUser() {
      this.$Progress.start();
      this.form
        .post("api/user")
        .then(() => {
          Fire.$emit("RefreshTable");
          $("#addNewUser").modal("hide");
          toast({
            type: "success",
            title: "User Created Successfully"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal("Failed!", "There was something wrong.", "warning");
          this.$Progress.fail();
          });
    }
  },
  mounted() {
    this.loadUsers();
    Fire.$on("RefreshTable", () => {
      this.loadUsers();
    });

    // setInterval(() => this.loadUsers(), 3000);
  }
};
</script>
