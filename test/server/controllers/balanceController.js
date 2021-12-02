
class BalanceController {
	async getAll(req,res){
		const {userId} =req.query
		let balances = await Balance.findAll({where:{userId}})
		return res.json(balances)
	}
	async getOne(req,res){
		const {userId}=req.params
		const balance = await Balance.findOne({where{userId}})
		return res.json(balance)
		}
}

module.exports=new BalanceController()