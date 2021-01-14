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
    
    @IBAction func confirmFinish(_ sender: Any) {
        let storyBoard: UIStoryboard = UIStoryboard(name: "Main", bundle: nil)
        let mainController = storyBoard.instantiateViewController(withIdentifier: "mainController") as! MainController;
        mainController.modalPresentationStyle = .fullScreen;
                self.present(mainController, animated: true, completion: nil)
    }
    
    override func viewWillAppear(_ animated: Bool) {
        finishMoney.text = (Global.lastsession["sum"] as? String ?? "0") + " ₽"
        finishTime.text = (Global.lastsession["duration"] as? String ?? "0") + " мин"
    }
    
}

