import Swal from 'sweetalert2'; 
<template>

  

    <section class="content">
     
       <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Category</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="">Admin</a></li>
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
       
      
         

        

          <div class="card">  

            <div class="card-header">
              <h3 class="card-title">Users </h3>
              <button class="btn btn-info" style="float: right;" @click="createUserModal" > Add new</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body"> 
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Registerd At</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users.data" :key="user.id">
                  <td>{{user.id}}</td>
                  <td>{{user.name}}</td>
                  <td>{{user.email}}</td>
                  <td>{{user.type | capitalize}}</td>
                  <td>{{user.created_at | myDate}}</td>
                  <td>
                  <a href="#"  class="btn btn-info" @click="editUserModal(user)" > <i class="fa fa-edit"></i> </a> 
                  <a href="#"  class="btn btn-danger" @click="deleteUser(user.id)">  <i class="fa fa-trash"></i></a>
                </td>
                </tr>

                </tbody>
                <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Registerd At</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <pagination :data="users" @pagination-change-page="getResults"></pagination>
             </div>

          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      </div><!-- /.container-fluid -->
    </div>
          <!-- /.card -->

 <!-- Modal -->
<div class="modal fade" id="adduser" tabindex="-1" role="dialog" aria-labelledby="adduserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">Create User</h5>
        <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">Update User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

     <form @submit.prevent=" editmode ? updateUser() : createUser()">
      
      <div class="modal-body">
       <!-- ... -->

        <div class="form-group">
          <label>Name</label>
          <input v-model="form.name" type="text" name="name" placeholder="Name" 
            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
          <has-error :form="form" field="name"></has-error>
       </div>

       

       <div class="form-group">
          <label>Email</label>
          <input v-model="form.email" type="email" name="email" placeholder="Email" 
            class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
          <has-error :form="form" field="email"></has-error>
       </div>

        <div class="form-group">
          <label>Password</label>
          <input v-model="form.password" type="password" name="password" placeholder="Password" 
            class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
          <has-error :form="form" field="password"></has-error>
       </div>

       <div class="form-group">
          <textarea v-model="form.bio" type="text" name="bio" placeholder="Short Bio For User (Optional)" 
            class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
          <has-error :form="form" field="bio"></has-error>
       </div>

       <div class="form-group">
          <select v-model="form.type" type="text" name="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
            <option value="">Select User Role</option>
            <option value="admin">Admin</option>
            <option value="user">Standard</option>
            <option value="author">Author</option></select>
          <has-error :form="form" field="type"></has-error>
       </div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button v-show="editmode" type="submit" class="btn btn-success">Update User</button>
        <button v-show="!editmode" type="submit" class="btn btn-primary">Create User</button>
      </div>

      </form>
    </div>
  </div>
</div>
    </section>
    <!-- /.content -->
</template>

<script>
    export default {
        
        data() {
          return {
            editmode: false,
            users: {},

            form: new Form({
              id:'',
              name: '',
              email: '',
              password: '',
              type: '',
              bio: '',
              photo: '',

            })
          }
        },



        methods: {

          loadUsers(){
              axios.get("api/user").then(({ data }) => (this.users = data));
            },

            getResults(page = 1) {
              axios.get('api/user?page=' + page).then(response => {this.users = response.data;});
            },

            createUserModal(){
              this.editmode = false;
              this.form.reset();
              $('#adduser').modal('show');

            },

            editUserModal(user){
              this.editmode = true;
              this.form.reset();
              $('#adduser').modal('show');
              this.form.fill(user);

            },
            createUser(){
              this.$Progress.start();
              this.form.post('api/user').then(()=>{
                 $('#adduser').modal('hide');
                 Toast.fire({
                      icon: 'success',
                      title: 'User Created Successfully'
                    });
                 this.loadUsers();

                 this.$Progress.finish();
               })

              .catch(()=>{
                Toast.fire({
                      icon: 'error',
                      title: 'Failed Unable To Create User'
                    });
                this.$Progress.fail();
              })

             },

             updateUser(){
              this.$Progress.start();
              this.form.patch('api/user/'+this.form.id).then(()=>{
                 $('#adduser').modal('hide');
                 Toast.fire({
                      icon: 'success',
                      title: 'User Updatd Successfully'
                    });
                 this.loadUsers();

                 this.$Progress.finish();
               })

              .catch(()=>{
                Toast.fire({
                      icon: 'error',
                      title: 'Failed Unable To Update User'
                    });
                this.$Progress.fail();
              })

             },

             deleteUser(id){
              const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                          confirmButton: 'btn btn-success',
                          cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: true
                      })
                      this.$Progress.start();
                      swalWithBootstrapButtons.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                      }).then((result) => {
                        
                        
                        
                        if (result.value) {

                               this.form.delete('api/user/'+id).then(()=>{ 
                                swalWithBootstrapButtons.fire(
                                  'Deleted!',
                                  'User has been deleted Successfully.',
                                  'success'
                                );
                                
                                this.loadUsers();
                                

                              }).catch(()=>{
                                this.$Progress.fail();
                                swalWithBootstrapButtons.fire(
                                  'Error',
                                  'User Cant Be  deleted',
                                  'error'
                                )
                        

                              })

                    } else if (
                          /* Read more about handling dismissals below */
                          result.dismiss === Swal.DismissReason.cancel
                        ) {
                          swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your User is Safe',
                            'error'
                          )
                          this.$Progress.fail();
                        }
                      })
             },
            
        },

        mounted() {

              Fire.$on('searching', () => {
              let query = this.$parent.search;
              axios.get('api/findUser?q=' + query)
              .then((data)=>{
                this.users = data.data;
              })
              .catch(()=>{

              });
            })

            

            console.log('Component mounted.');
            this.$Progress.start();
            this.loadUsers();
            this.$Progress.finish();
            
            //setTimeout(this.$Progress.finish(), 30000000000);
            
        }
    }
</script>

        