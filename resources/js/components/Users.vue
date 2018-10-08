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

                    <a href="" data-toggle="modal" data-target="#addNewUser">
                        <i class="fas fa-user-plus fa-3x text-green"></i>
                    </a>

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
                        <a href="#">
                            <i class="fas fa-user-edit fa-lg text-orange"></i>
                        </a>
                        &nbsp;
                        <a href="#">
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
                <h5 class="modal-title" id="addNewUserTitle">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form @submit.prevent="createUser">
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
                <button type="button" data-dismiss="modal" class="btn" style="background-color: white;"><i class="fa fa-times-circle fa-3x text-fade-red"></i></button>
                <button class="btn" type="submit" style="background-color: white;"><i class="fa fa-check-circle fa-3x text-cyan"></i></button>
            </div>
        </form>

            </div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                users: {},
                form: new Form({
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                })
            }
        },
        methods:{
            loadUsers(){
                axios.get('api/user').then(({ data }) => (this.users = data.data));
            },
            createUser(){
                this.form.post('api/user');
            }
        },
        created() {
            this.loadUsers();
        }
    }
</script>
