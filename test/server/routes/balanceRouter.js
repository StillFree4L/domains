const Router=require('express')
const router=new Router()
const balanceController=require('../controllers/userController')
const checkRole= require('../middleware/checkRoleMiddleware')


router.get('/',checkRole('ADMIN'),balanceController.getOne)
router.get('/',balanceController.getAll)

module.exports=router