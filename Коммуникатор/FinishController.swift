//
//  FinishController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 10.01.2021.
//

import UIKit

class FinishController: UIViewController {
    
   
    @IBOutlet weak var finishMoney: UILabel!
    @IBOutlet weak var finishTime: UILabel!
    
    @IBOutlet weak var clickFinish: UIButton!
    override func viewDidLoad() {
        super.viewDidLoad()
    }
    
    
    override func viewWillAppear(_ animated: Bool) {
        finishMoney.text = (Global.lastsession["sum"] as? String ?? "0") + " ₽"
        finishTime.text = (Global.lastsession["duration"] as? String ?? "0") + " мин"
    }
    
}

